<?php

namespace App\Support;

use App\Models\Banner;
use App\Models\Branch;
use App\Models\CalendarEvent;
use App\Models\Category;
use App\Models\Download;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\ListItem;
use App\Models\LoanService;
use App\Models\Notice;
use App\Models\Person;
use App\Models\SavingPlan;
use App\Models\SiteContent;
use App\Models\SocialLink;
use App\Models\Statistic;

/**
 * Declarative definition of every admin-managed module. One generic controller
 * (Admin\ResourceController) renders list/create/edit/delete/publish screens
 * and validates input from these definitions, so each module stays small and
 * consistent.
 */
class AdminResources
{
    public const ICONS = [
        'savings' => 'Savings', 'loan' => 'Loan', 'home' => 'Home', 'education' => 'Education', 'business' => 'Business',
        'agriculture' => 'Agriculture', 'vehicle' => 'Vehicle', 'health' => 'Health', 'shield' => 'Shield',
        'users' => 'People', 'star' => 'Star', 'heart' => 'Heart', 'target' => 'Target', 'eye' => 'Eye',
        'handshake' => 'Handshake', 'chart' => 'Chart', 'building' => 'Building', 'award' => 'Award', 'calendar' => 'Calendar',
    ];

    private const IMG = 'image|mimes:jpg,jpeg,png,webp|max:5120';

    private const DOC = 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';

    // ---------------------------------------------------------------- field builders

    private static function f(string $name, string $type, string $label, string $rules = 'nullable|string|max:190', array $extra = []): array
    {
        return array_merge(compact('name', 'type', 'label', 'rules'), $extra);
    }

    /** English + Nepali pair. Only the English one is required. */
    private static function bi(string $base, string $label, string $type = 'text', bool $required = false, array $extra = []): array
    {
        $max = $type === 'textarea' ? 'max:5000' : 'max:190';
        if (isset($extra['max'])) {
            $max = 'max:'.$extra['max'];
            unset($extra['max']);
        }

        return [
            self::f($base.'_en', $type, $label.' (English)', ($required ? 'required' : 'nullable').'|string|'.$max, $extra),
            self::f($base.'_ne', $type, $label.' (नेपाली)', 'nullable|string|'.$max, $extra),
        ];
    }

    private static function image(string $name, string $label, string $dir, bool $required = false, array $extra = []): array
    {
        return self::f($name, 'image', $label, ($required ? 'required|' : 'nullable|').self::IMG, array_merge(['dir' => $dir, 'span' => 2, 'required' => $required], $extra));
    }

    private static function file(string $name, string $label, string $dir, bool $required = false): array
    {
        return self::f($name, 'file', $label, ($required ? 'required|' : 'nullable|').self::DOC, ['dir' => $dir, 'span' => 2, 'required' => $required]);
    }

    private static function order(): array
    {
        return self::f('sort_order', 'number', 'Display order', 'nullable|integer|min:0|max:100000', ['default' => 0, 'help' => 'Lower numbers appear first.']);
    }

    private static function active(string $name = 'is_active', string $label = 'Active (visible on the website)'): array
    {
        return self::f($name, 'checkbox', $label, 'boolean', ['default' => true]);
    }

    private static function categorySelect(string $type, string $label = 'Category'): array
    {
        return self::f('category_id', 'select', $label, 'nullable|integer|exists:categories,id', ['source' => ['categories', $type]]);
    }

    // ---------------------------------------------------------------- definitions

    /** @return array<string,array> */
    public static function all(): array
    {
        static $defs = null;

        return $defs ??= self::build();
    }

    public static function get(string $key): ?array
    {
        $d = self::all()[$key] ?? null;

        return $d ? $d + ['key' => $key] : null;
    }

    private static function people(string $group, string $label, string $singular, bool $withSection, bool $tenure): array
    {
        $fields = [
            ...self::bi('name', 'Full name', 'text', true),
            ...self::bi('designation', 'Designation'),
        ];
        if ($withSection) {
            $fields[] = self::f('section', 'select', 'Committee / sub-committee', 'nullable|string|in:'.implode(',', array_keys(Committee::SECTIONS)), [
                'options' => Committee::options(), 'help' => 'Controls the group this person appears under on the Board Members page.',
            ]);
        }
        if ($tenure) {
            $fields[] = self::f('tenure', 'text', 'Tenure / period', 'nullable|string|max:120', ['help' => 'e.g. 2075–2079 B.S.']);
        }
        $fields[] = self::f('phone', 'tel', 'Phone (optional)', 'nullable|string|max:40');
        $fields[] = self::f('email', 'email', 'Email (optional)', 'nullable|email|max:190');
        array_push($fields, ...self::bi('bio', 'Short biography', 'textarea', false, ['span' => 2, 'rows' => 4]));
        $fields[] = self::image('photo', 'Profile photo', 'people', false, ['library' => 'people', 'help' => 'JPG/PNG/WebP up to 5 MB. You can also pick one of the supplied portraits below.']);
        $fields[] = self::order();
        $fields[] = self::active();

        return [
            'label' => $label, 'singular' => $singular, 'group' => 'People', 'icon' => 'users',
            'model' => Person::class, 'scope' => ['group' => $group],
            'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => array_values(array_filter([
                ['name' => 'photo', 'label' => 'Photo', 'type' => 'image'],
                ['name' => 'name_en', 'label' => 'Name', 'type' => 'text', 'sub' => 'name_ne'],
                ['name' => 'designation_en', 'label' => 'Designation', 'type' => 'text', 'sub' => 'designation_ne'],
                $withSection ? ['name' => 'section', 'label' => 'Section', 'type' => 'section'] : null,
                ['name' => 'sort_order', 'label' => 'Order', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ])),
            'search' => ['name_en', 'name_ne', 'designation_en', 'designation_ne'],
            'filters' => array_filter([
                'is_active' => ['label' => 'Status', 'type' => 'bool'],
                'section' => $withSection ? ['label' => 'Section', 'type' => 'select', 'options' => Committee::options()] : null,
            ]),
            'toggle' => 'is_active',
            'fields' => $fields,
        ];
    }

    private static function build(): array
    {
        $defs = [];

        $defs['banners'] = [
            'label' => 'Homepage Banners', 'singular' => 'Banner', 'group' => 'Website', 'icon' => 'image',
            'model' => Banner::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'sort_order', 'label' => 'Order', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['title_en', 'title_ne'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('title', 'Headline', 'text', true),
                ...self::bi('subtitle', 'Sub-headline', 'textarea', false, ['span' => 1, 'rows' => 3, 'max' => 500]),
                self::image('image', 'Banner image', 'banners', true, ['help' => 'Wide image recommended (about 1920×900). Optimised automatically.']),
                ...self::bi('button_text', 'Primary button text', 'text', false, ['max' => 60]),
                self::f('button_url', 'url', 'Primary button link', 'nullable|string|max:300', ['help' => 'e.g. /become-a-member or https://…']),
                ...self::bi('button2_text', 'Secondary button text', 'text', false, ['max' => 60]),
                self::f('button2_url', 'url', 'Secondary button link', 'nullable|string|max:300'),
                self::order(), self::active(),
            ],
        ];

        $defs['about'] = [
            'label' => 'About Us, Vision & Mission', 'singular' => 'Content block', 'group' => 'Website', 'icon' => 'info',
            'model' => SiteContent::class, 'order' => [['id', 'asc']],
            'can' => ['create' => false, 'delete' => false],
            'columns' => [
                ['name' => 'key', 'label' => 'Block', 'type' => 'key'],
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'updated_at', 'label' => 'Updated', 'type' => 'datetime'],
            ],
            'search' => ['title_en', 'title_ne', 'key'], 'filters' => [],
            'fields' => [
                ...self::bi('title', 'Title'),
                ...self::bi('body', 'Content', 'textarea', false, ['span' => 2, 'rows' => 9, 'help' => 'Blank line = new paragraph.']),
                self::image('image', 'Image (optional)', 'content'),
            ],
        ];

        foreach ([
            'objectives' => [ListItem::OBJECTIVE, 'Objectives', 'Objective'],
            'core_values' => [ListItem::VALUE, 'Core Values', 'Core value'],
            'achievements' => [ListItem::ACHIEVEMENT, 'Institutional Achievements', 'Achievement'],
        ] as $key => [$type, $label, $singular]) {
            $defs[$key] = [
                'label' => $label, 'singular' => $singular, 'group' => 'Website', 'icon' => 'target',
                'model' => ListItem::class, 'scope' => ['type' => $type], 'order' => [['sort_order', 'asc'], ['id', 'asc']],
                'columns' => [
                    ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                    ['name' => 'sort_order', 'label' => 'Order', 'type' => 'text'],
                    ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
                ],
                'search' => ['title_en', 'title_ne'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
                'toggle' => 'is_active',
                'fields' => [
                    ...self::bi('title', 'Title', 'text', true),
                    ...self::bi('description', 'Description', 'textarea', false, ['rows' => 3, 'max' => 1000]),
                    self::f('icon', 'select', 'Icon', 'nullable|string|in:'.implode(',', array_keys(self::ICONS)), ['options' => self::ICONS]),
                    self::order(), self::active(),
                ],
            ];
        }

        $defs['statistics'] = [
            'label' => 'Cooperative Statistics', 'singular' => 'Statistic', 'group' => 'Website', 'icon' => 'chart',
            'model' => Statistic::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'label_en', 'label' => 'Label', 'type' => 'text', 'sub' => 'label_ne'],
                ['name' => 'value', 'label' => 'Value', 'type' => 'stat'],
                ['name' => 'is_draft', 'label' => 'Data', 'type' => 'draft'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['label_en', 'label_ne'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('label', 'Label', 'text', true),
                self::f('prefix', 'text', 'Prefix', 'nullable|string|max:20', ['help' => 'e.g. Rs.']),
                self::f('value', 'text', 'Value', 'required|string|max:40', ['help' => 'Numbers animate on the homepage, e.g. 1250 or 85.5']),
                self::f('suffix', 'text', 'Suffix', 'nullable|string|max:20', ['help' => 'e.g. +, M, Cr, Years']),
                self::f('icon', 'select', 'Icon', 'nullable|string|in:'.implode(',', array_keys(self::ICONS)), ['options' => self::ICONS]),
                self::f('is_draft', 'checkbox', 'Sample / draft figure (shows a "sample" tag on the website)', 'boolean', ['default' => false, 'help' => 'Untick once you have entered the official figure.']),
                self::order(), self::active(),
            ],
        ];

        $defs['board'] = self::people(Person::BOARD, 'Board Members & Committees', 'Board member', true, false);
        $defs['employees'] = self::people(Person::EMPLOYEE, 'Current Employees', 'Employee', false, false);
        $defs['former_committee'] = self::people(Person::FORMER_COMMITTEE, 'Former Committee', 'Former committee member', false, true);
        $defs['former_employees'] = self::people(Person::FORMER_EMPLOYEE, 'Former Employees', 'Former employee', false, true);

        $defs['saving_plans'] = [
            'label' => 'Saving Plans', 'singular' => 'Saving plan', 'group' => 'Services', 'icon' => 'savings',
            'model' => SavingPlan::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'name_en', 'label' => 'Plan', 'type' => 'text', 'sub' => 'name_ne'],
                ['name' => 'interest_rate', 'label' => 'Rate', 'type' => 'percent'],
                ['name' => 'is_draft', 'label' => 'Data', 'type' => 'draft'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['name_en', 'name_ne'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('name', 'Plan name', 'text', true),
                ...self::bi('summary', 'Short summary', 'textarea', false, ['rows' => 2, 'max' => 400]),
                ...self::bi('description', 'Description', 'textarea', false, ['span' => 1, 'rows' => 6]),
                self::f('interest_rate', 'decimal', 'Interest rate (% p.a.)', 'nullable|numeric|min:0|max:100', ['help' => 'Leave blank until the official rate is confirmed.']),
                self::f('min_deposit', 'decimal', 'Minimum deposit (Rs.)', 'nullable|numeric|min:0|max:999999999999'),
                ...self::bi('duration', 'Duration', 'text', false, ['help' => 'e.g. 12 months']),
                ...self::bi('eligibility', 'Eligibility', 'textarea', false, ['rows' => 3]),
                ...self::bi('features', 'Features & benefits', 'textarea', false, ['rows' => 5, 'help' => 'One benefit per line.']),
                self::image('image', 'Image', 'plans'),
                self::f('icon', 'select', 'Icon', 'nullable|string|in:'.implode(',', array_keys(self::ICONS)), ['options' => self::ICONS]),
                self::f('is_draft', 'checkbox', 'Sample / draft data (shows a "sample" tag on the website)', 'boolean', ['default' => false]),
                self::order(), self::active('is_active', 'Active (visible on the website)'),
            ],
        ];

        $defs['loan_services'] = [
            'label' => 'Loan Services', 'singular' => 'Loan service', 'group' => 'Services', 'icon' => 'loan',
            'model' => LoanService::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'name_en', 'label' => 'Loan', 'type' => 'text', 'sub' => 'name_ne'],
                ['name' => 'interest_rate', 'label' => 'Rate', 'type' => 'percent'],
                ['name' => 'is_draft', 'label' => 'Data', 'type' => 'draft'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['name_en', 'name_ne'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('name', 'Loan name', 'text', true),
                ...self::bi('summary', 'Short summary', 'textarea', false, ['rows' => 2, 'max' => 400]),
                ...self::bi('description', 'Description', 'textarea', false, ['rows' => 6]),
                self::f('interest_rate', 'decimal', 'Interest rate (% p.a.)', 'nullable|numeric|min:0|max:100', ['help' => 'Leave blank until the official rate is confirmed.']),
                self::f('min_amount', 'decimal', 'Minimum loan amount (Rs.)', 'nullable|numeric|min:0|max:999999999999'),
                self::f('max_amount', 'decimal', 'Maximum loan amount (Rs.)', 'nullable|numeric|min:0|max:999999999999|gte:min_amount'),
                ...self::bi('duration', 'Repayment duration', 'text', false, ['help' => 'e.g. Up to 5 years']),
                ...self::bi('eligibility', 'Eligibility criteria', 'textarea', false, ['rows' => 4]),
                ...self::bi('documents', 'Required documents', 'textarea', false, ['rows' => 5, 'help' => 'One document per line.']),
                ...self::bi('terms', 'Terms and conditions', 'textarea', false, ['rows' => 5, 'help' => 'One term per line, or plain paragraphs.']),
                self::image('image', 'Image', 'loans'),
                self::f('icon', 'select', 'Icon', 'nullable|string|in:'.implode(',', array_keys(self::ICONS)), ['options' => self::ICONS]),
                self::f('is_draft', 'checkbox', 'Sample / draft data (shows a "sample" tag on the website)', 'boolean', ['default' => false]),
                self::order(), self::active(),
            ],
        ];

        $defs['branches'] = [
            'label' => 'Branches', 'singular' => 'Branch', 'group' => 'Services', 'icon' => 'building',
            'model' => Branch::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'name_en', 'label' => 'Branch', 'type' => 'text', 'sub' => 'name_ne'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
                ['name' => 'is_draft', 'label' => 'Data', 'type' => 'draft'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['name_en', 'name_ne', 'address_en', 'phone'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('name', 'Branch name', 'text', true),
                ...self::bi('address', 'Address'),
                self::f('phone', 'tel', 'Contact number', 'nullable|string|max:60'),
                self::f('email', 'email', 'Email', 'nullable|email|max:190'),
                ...self::bi('manager', 'Branch manager'),
                ...self::bi('opening_hours', 'Opening hours'),
                self::f('map_url', 'url', 'Google Maps link', 'nullable|url|max:1000', ['span' => 2, 'help' => 'Paste the share link from Google Maps.']),
                self::image('image', 'Branch image', 'branches'),
                self::f('is_head_office', 'checkbox', 'This is the head office', 'boolean', ['default' => false]),
                self::f('is_draft', 'checkbox', 'Sample / draft data (shows a "sample" tag on the website)', 'boolean', ['default' => false]),
                self::order(), self::active(),
            ],
        ];

        foreach ([
            'notices' => [Notice::GENERAL, 'Notices', 'Notice'],
            'auction_notices' => [Notice::AUCTION, 'Auction Notices', 'Auction notice'],
        ] as $key => [$type, $label, $singular]) {
            $defs[$key] = [
                'label' => $label, 'singular' => $singular, 'group' => 'Publishing', 'icon' => 'bell',
                'model' => Notice::class, 'scope' => ['type' => $type],
                'with' => ['category'], 'order' => [['published_at', 'desc'], ['id', 'desc']],
                'columns' => [
                    ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne', 'flag' => 'is_important'],
                    ['name' => 'category.name_en', 'label' => 'Category', 'type' => 'text'],
                    ['name' => 'published_at', 'label' => 'Published', 'type' => 'datetime'],
                    ['name' => 'expires_at', 'label' => 'Expires', 'type' => 'datetime'],
                    ['name' => 'is_published', 'label' => 'Status', 'type' => 'published'],
                ],
                'search' => ['title_en', 'title_ne'],
                'filters' => [
                    'is_published' => ['label' => 'Status', 'type' => 'bool', 'true' => 'Published', 'false' => 'Unpublished'],
                    'is_important' => ['label' => 'Important', 'type' => 'bool', 'true' => 'Important only', 'false' => 'Not important'],
                    'category_id' => ['label' => 'Category', 'type' => 'select', 'source' => ['categories', 'notice']],
                ],
                'toggle' => 'is_published',
                'fields' => [
                    ...self::bi('title', 'Title', 'text', true),
                    self::categorySelect('notice'),
                    self::f('published_at', 'datetime', 'Publication date', 'nullable|date', ['default' => now()->format('Y-m-d\TH:i')]),
                    self::f('expires_at', 'datetime', 'Expiry date (optional)', 'nullable|date|after_or_equal:published_at'),
                    ...self::bi('body', 'Notice text', 'textarea', false, ['span' => 1, 'rows' => 9]),
                    self::file('attachment', 'PDF / document attachment', 'notices'),
                    self::f('is_important', 'checkbox', 'Mark as important (highlighted)', 'boolean', ['default' => false]),
                    self::f('is_published', 'checkbox', 'Published', 'boolean', ['default' => true]),
                ],
            ];
        }

        $defs['photos'] = [
            'label' => 'Photo Gallery', 'singular' => 'Photo', 'group' => 'Publishing', 'icon' => 'image',
            'model' => GalleryPhoto::class, 'with' => ['category'], 'order' => [['sort_order', 'asc'], ['id', 'desc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Photo', 'type' => 'image'],
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'category.name_en', 'label' => 'Category', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['title_en', 'title_ne', 'description_en'],
            'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool'], 'category_id' => ['label' => 'Category', 'type' => 'select', 'source' => ['categories', 'gallery']]],
            'toggle' => 'is_active',
            'fields' => [
                self::image('image', 'Photo', 'gallery', true, ['max_width' => 2000]),
                ...self::bi('title', 'Title'),
                ...self::bi('description', 'Description', 'textarea', false, ['rows' => 3, 'max' => 1000]),
                self::categorySelect('gallery'),
                self::order(), self::active(),
            ],
        ];

        $defs['videos'] = [
            'label' => 'Video Gallery', 'singular' => 'Video', 'group' => 'Publishing', 'icon' => 'video',
            'model' => GalleryVideo::class, 'with' => ['category'], 'order' => [['sort_order', 'asc'], ['id', 'desc']],
            'columns' => [
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'url', 'label' => 'Link', 'type' => 'text'],
                ['name' => 'category.name_en', 'label' => 'Category', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['title_en', 'title_ne', 'url'],
            'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool'], 'category_id' => ['label' => 'Category', 'type' => 'select', 'source' => ['categories', 'video']]],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('title', 'Title', 'text', true),
                self::f('url', 'url', 'Video link (YouTube / Vimeo)', 'required|url|max:500', ['span' => 2]),
                ...self::bi('description', 'Description', 'textarea', false, ['rows' => 3, 'max' => 1000]),
                self::categorySelect('video'),
                self::image('thumbnail', 'Custom thumbnail (optional)', 'videos', false, ['help' => 'YouTube thumbnails are used automatically when blank.']),
                self::order(), self::active(),
            ],
        ];

        $defs['events'] = [
            'label' => 'Events & Activities', 'singular' => 'Event', 'group' => 'Publishing', 'icon' => 'calendar',
            'model' => Event::class, 'with' => ['category'], 'order' => [['event_date', 'desc'], ['id', 'desc']],
            'columns' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'event_date', 'label' => 'Date', 'type' => 'date'],
                ['name' => 'category.name_en', 'label' => 'Category', 'type' => 'text'],
                ['name' => 'is_published', 'label' => 'Status', 'type' => 'published'],
            ],
            'search' => ['title_en', 'title_ne', 'location_en'],
            'filters' => [
                'is_published' => ['label' => 'Status', 'type' => 'bool', 'true' => 'Published', 'false' => 'Unpublished'],
                'category_id' => ['label' => 'Category', 'type' => 'select', 'source' => ['categories', 'event']],
            ],
            'toggle' => 'is_published',
            'fields' => [
                ...self::bi('title', 'Title', 'text', true),
                self::f('event_date', 'date', 'Event date', 'required|date'),
                self::f('end_date', 'date', 'End date (optional)', 'nullable|date|after_or_equal:event_date'),
                ...self::bi('location', 'Location'),
                self::categorySelect('event'),
                ...self::bi('description', 'Description', 'textarea', false, ['rows' => 8]),
                self::image('image', 'Cover image', 'events'),
                self::f('is_published', 'checkbox', 'Published', 'boolean', ['default' => true]),
            ],
        ];

        $defs['calendar'] = [
            'label' => 'Digital Calendar', 'singular' => 'Calendar entry', 'group' => 'Publishing', 'icon' => 'calendar',
            'model' => CalendarEvent::class, 'order' => [['event_date', 'desc'], ['id', 'desc']],
            'columns' => [
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'type', 'label' => 'Type', 'type' => 'key'],
                ['name' => 'event_date', 'label' => 'Date', 'type' => 'date'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['title_en', 'title_ne'],
            'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool'], 'type' => ['label' => 'Type', 'type' => 'select', 'options' => CalendarEvent::TYPES]],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('title', 'Title', 'text', true),
                self::f('type', 'select', 'Type', 'required|string|in:'.implode(',', array_keys(CalendarEvent::TYPES)), ['options' => CalendarEvent::TYPES, 'default' => 'event']),
                self::f('event_date', 'date', 'Date', 'required|date'),
                self::f('end_date', 'date', 'End date (optional)', 'nullable|date|after_or_equal:event_date'),
                ...self::bi('description', 'Note', 'textarea', false, ['rows' => 3, 'max' => 1000]),
                self::active(),
            ],
        ];

        $defs['downloads'] = [
            'label' => 'Downloads & Documents', 'singular' => 'Document', 'group' => 'Publishing', 'icon' => 'download',
            'model' => Download::class, 'with' => ['category'], 'order' => [['sort_order', 'asc'], ['id', 'desc']],
            'columns' => [
                ['name' => 'title_en', 'label' => 'Title', 'type' => 'text', 'sub' => 'title_ne'],
                ['name' => 'category.name_en', 'label' => 'Category', 'type' => 'text'],
                ['name' => 'download_count', 'label' => 'Downloads', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['title_en', 'title_ne', 'description_en'],
            'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool'], 'category_id' => ['label' => 'Category', 'type' => 'select', 'source' => ['categories', 'download']]],
            'toggle' => 'is_active',
            'fields' => [
                ...self::bi('title', 'Title', 'text', true),
                self::categorySelect('download'),
                self::file('file', 'File', 'downloads', true),
                ...self::bi('description', 'Description', 'textarea', false, ['rows' => 3, 'max' => 1000]),
                self::order(), self::active(),
            ],
        ];

        $defs['social_links'] = [
            'label' => 'Social Media Links', 'singular' => 'Social link', 'group' => 'Website', 'icon' => 'share',
            'model' => SocialLink::class, 'order' => [['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'platform', 'label' => 'Platform', 'type' => 'key'],
                ['name' => 'url', 'label' => 'URL', 'type' => 'text'],
                ['name' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
            ],
            'search' => ['platform', 'url'], 'filters' => ['is_active' => ['label' => 'Status', 'type' => 'bool']],
            'toggle' => 'is_active',
            'fields' => [
                self::f('platform', 'select', 'Platform', 'required|string|in:'.implode(',', array_keys(SocialLink::PLATFORMS)), ['options' => SocialLink::PLATFORMS]),
                self::f('url', 'url', 'Link (https://…)', 'required|url|max:300'),
                self::order(), self::active(),
            ],
        ];

        $defs['categories'] = [
            'label' => 'Categories', 'singular' => 'Category', 'group' => 'System', 'icon' => 'tag',
            'model' => Category::class, 'order' => [['type', 'asc'], ['sort_order', 'asc'], ['id', 'asc']],
            'columns' => [
                ['name' => 'name_en', 'label' => 'Name', 'type' => 'text', 'sub' => 'name_ne'],
                ['name' => 'type', 'label' => 'Used for', 'type' => 'category_type'],
                ['name' => 'sort_order', 'label' => 'Order', 'type' => 'text'],
            ],
            'search' => ['name_en', 'name_ne'],
            'filters' => ['type' => ['label' => 'Used for', 'type' => 'select', 'options' => Category::TYPES]],
            'fields' => [
                self::f('type', 'select', 'Used for', 'required|string|in:'.implode(',', array_keys(Category::TYPES)), ['options' => Category::TYPES, 'help' => 'Categories for notices, gallery, events, downloads and membership types are managed here.']),
                ...self::bi('name', 'Name', 'text', true),
                self::order(),
            ],
        ];

        return $defs;
    }

    // ---------------------------------------------------------------- navigation

    /** Sidebar structure. `route` = [name, params]; `roles` = null (everyone) or role list. */
    public static function navigation(): array
    {
        $res = fn (string $key, ?string $label = null) => [
            'label' => $label ?? self::all()[$key]['label'],
            'route' => ['admin.resource.index', ['resource' => $key]],
            'match' => 'admin/r/'.$key.'*',
            'icon' => self::all()[$key]['icon'],
        ];

        return [
            ['title' => null, 'items' => [
                ['label' => 'Dashboard', 'route' => ['admin.dashboard', []], 'match' => 'admin', 'icon' => 'dashboard'],
            ]],
            ['title' => 'Website', 'items' => [
                ['label' => 'Website Settings', 'route' => ['admin.settings.edit', ['section' => 'general']], 'match' => 'admin/settings/general', 'icon' => 'settings'],
                ['label' => 'Logo & Favicon', 'route' => ['admin.settings.edit', ['section' => 'logo']], 'match' => 'admin/settings/logo', 'icon' => 'image'],
                $res('banners'),
                $res('about'),
                $res('objectives'), $res('core_values'), $res('achievements'),
                $res('statistics'),
                ['label' => 'Contact & Map', 'route' => ['admin.settings.edit', ['section' => 'contact']], 'match' => 'admin/settings/contact', 'icon' => 'phone'],
                ['label' => 'Footer Settings', 'route' => ['admin.settings.edit', ['section' => 'footer']], 'match' => 'admin/settings/footer', 'icon' => 'settings'],
                $res('social_links'),
                ['label' => 'SEO Settings', 'route' => ['admin.settings.edit', ['section' => 'seo']], 'match' => 'admin/settings/seo', 'icon' => 'search'],
            ]],
            ['title' => 'People', 'items' => [
                $res('board'), $res('employees'), $res('former_committee'), $res('former_employees'),
            ]],
            ['title' => 'Services', 'items' => [
                $res('saving_plans'), $res('loan_services'), $res('branches'),
            ]],
            ['title' => 'Publishing', 'items' => [
                $res('notices'), $res('auction_notices'), $res('photos'), $res('videos'), $res('events'), $res('calendar'), $res('downloads'),
            ]],
            ['title' => 'Inbox', 'items' => [
                ['label' => 'Membership Applications', 'route' => ['admin.applications.index', []], 'match' => 'admin/applications*', 'icon' => 'users', 'badge' => 'applications'],
                ['label' => 'Contact Messages', 'route' => ['admin.messages.index', []], 'match' => 'admin/messages*', 'icon' => 'mail', 'badge' => 'messages'],
            ]],
            ['title' => 'System', 'items' => [
                $res('categories'),
                ['label' => 'Language & Content', 'route' => ['admin.translations.index', []], 'match' => 'admin/translations*', 'icon' => 'language'],
                ['label' => 'Admin Users', 'route' => ['admin.users.index', []], 'match' => 'admin/users*', 'icon' => 'shield', 'roles' => ['super_admin']],
                ['label' => 'My Password', 'route' => ['admin.password.edit', []], 'match' => 'admin/password*', 'icon' => 'key'],
            ]],
        ];
    }

    // ---------------------------------------------------------------- settings

    public static function settingsSections(): array
    {
        return [
            'general' => [
                'title' => 'Website Settings', 'description' => 'Name, contact details and office hours shown in the header and footer.',
                'fields' => [
                    ...self::bi('site_name', 'Cooperative name', 'text', true),
                    ...self::bi('tagline', 'Tagline'),
                    self::f('established_year', 'number', 'Year established (A.D.)', 'nullable|integer|min:1900|max:2100', ['help' => 'Used to calculate "Years of Service" if you wish.']),
                    self::f('registration_no', 'text', 'Registration number', 'nullable|string|max:60'),
                    self::f('phone', 'tel', 'Phone number', 'nullable|string|max:60'),
                    self::f('email', 'email', 'Email address', 'nullable|email|max:190'),
                    ...self::bi('hours', 'Office hours', 'text', false, ['help' => 'Shown in the top bar.']),
                    ...self::bi('address', 'Office address'),
                ],
            ],
            'logo' => [
                'title' => 'Logo & Favicon', 'description' => 'Upload your logo (PNG/JPG/WebP). A default Sajilo Bachat mark is used until you do.',
                'fields' => [
                    self::image('logo', 'Logo', 'branding', false, ['max_width' => 800]),
                    self::image('favicon', 'Favicon (square PNG)', 'branding', false, ['max_width' => 256]),
                    self::image('og_image', 'Social sharing image (1200×630)', 'branding', false, ['max_width' => 1200]),
                ],
            ],
            'contact' => [
                'title' => 'Contact & Map', 'description' => 'Google Maps embed shown on the Contact page.',
                'fields' => [
                    self::f('map_embed_url', 'url', 'Google Maps embed URL', 'nullable|url|max:1500|starts_with:https://www.google.com/maps/embed,https://www.google.com/maps?', ['span' => 2, 'help' => 'In Google Maps choose Share → Embed a map, then paste only the URL found inside src="…".']),
                    self::f('map_link', 'url', 'Google Maps place link', 'nullable|url|max:1000', ['span' => 2]),
                    ...self::bi('contact_note', 'Note shown beside the contact form', 'textarea', false, ['rows' => 3, 'max' => 500]),
                    self::f('contact_recipient', 'email', 'Send a copy of contact messages to (optional)', 'nullable|email|max:190', ['help' => 'Requires mail to be configured in .env.']),
                ],
            ],
            'footer' => [
                'title' => 'Footer Settings', 'description' => 'Text shown in the site footer.',
                'fields' => [
                    ...self::bi('footer_about', 'Footer description', 'textarea', false, ['rows' => 3, 'max' => 600]),
                    ...self::bi('copyright', 'Copyright line'),
                    self::f('show_draft_badges', 'checkbox', 'Show "sample" tags on draft data', 'boolean', ['default' => true, 'help' => 'Turn off once all sample content has been replaced.']),
                ],
            ],
            'seo' => [
                'title' => 'SEO Settings', 'description' => 'Default search-engine description and keywords.',
                'fields' => [
                    ...self::bi('meta_description', 'Default meta description', 'textarea', false, ['rows' => 3, 'max' => 300]),
                    self::f('meta_keywords', 'text', 'Meta keywords', 'nullable|string|max:300', ['span' => 2]),
                ],
            ],
        ];
    }

    // ---------------------------------------------------------------- helpers

    /** Resolve the options of a select field into [value => label]. */
    public static function options(array $field): array
    {
        if (isset($field['options'])) {
            return $field['options'];
        }
        if (isset($field['source']) && $field['source'][0] === 'categories') {
            return Category::ofType($field['source'][1])->get()
                ->mapWithKeys(fn ($c) => [$c->id => $c->name_en.($c->name_ne ? ' / '.$c->name_ne : '')])
                ->all();
        }

        return [];
    }

    /** Validation rules for the whole form, derived from the field list. */
    public static function rules(array $fields, bool $updating): array
    {
        $rules = [];
        foreach ($fields as $f) {
            $rule = $f['rules'];
            if (in_array($f['type'], ['image', 'file'], true)) {
                // On edit the existing file satisfies "required".
                if ($updating) {
                    $rule = preg_replace('/^required\|/', 'nullable|', $rule);
                }
                $rules[$f['name'].'__existing'] = ['nullable', 'string', 'max:300'];
                $rules['remove_'.$f['name']] = ['nullable', 'boolean'];
            }
            $rules[$f['name']] = $rule;
        }

        return $rules;
    }
}

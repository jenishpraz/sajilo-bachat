<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Support\Committee;

class PeopleController extends Controller
{
    public function board()
    {
        $people = Person::active()->inGroup(Person::BOARD)->ordered()->get()->groupBy('section');

        // Known sections first (in PDF order), then any custom ones.
        $sections = [];
        foreach (array_keys(Committee::SECTIONS) as $key) {
            if ($people->has($key)) {
                $sections[$key] = $people[$key];
            }
        }
        foreach ($people as $key => $list) {
            if (! isset($sections[$key])) {
                $sections[$key ?: 'other'] = $list;
            }
        }

        return view('site.people.board', compact('sections'));
    }

    public function employees()
    {
        return $this->grid(Person::EMPLOYEE, 'people.employees', 'employees');
    }

    public function formerCommittee()
    {
        return $this->grid(Person::FORMER_COMMITTEE, 'people.former_committee', 'former-committee');
    }

    public function formerEmployees()
    {
        return $this->grid(Person::FORMER_EMPLOYEE, 'people.former_employees', 'former-employees');
    }

    private function grid(string $group, string $titleKey, string $slug)
    {
        $people = Person::active()->inGroup($group)->ordered()->paginate(24);

        return view('site.people.grid', ['people' => $people, 'titleKey' => $titleKey, 'slug' => $slug]);
    }
}

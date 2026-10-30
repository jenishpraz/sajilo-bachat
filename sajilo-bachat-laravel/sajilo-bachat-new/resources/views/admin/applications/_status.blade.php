@php $map = ['pending' => 'badge-gold', 'under_review' => 'badge-blue', 'approved' => 'badge-green', 'rejected' => 'badge-red']; @endphp
<span class="badge {{ $map[$status] ?? 'badge-gray' }}">{{ \App\Models\MembershipApplication::STATUSES[$status] ?? $status }}</span>

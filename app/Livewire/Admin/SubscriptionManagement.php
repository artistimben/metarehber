<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPlan = '';

    protected $queryString = ['search', 'filterStatus', 'filterPlan'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->when($this->filterPlan, function ($query) {
                $query->where('subscription_plan_id', $this->filterPlan);
            })
            ->latest()
            ->paginate(15);

        $plans = \App\Models\SubscriptionPlan::all();
        
        // İstatistikler
        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('is_active', true)->count(),
            'trial' => Subscription::where('is_trial', true)->where('is_active', true)->count(),
            'expiring_soon' => Subscription::where('is_active', true)
                ->whereDate('end_date', '<=', now()->addDays(7))
                ->whereDate('end_date', '>=', now())
                ->count(),
        ];

        return view('livewire.admin.subscription-management', [
            'subscriptions' => $subscriptions,
            'plans' => $plans,
            'stats' => $stats,
        ]);
    }
}

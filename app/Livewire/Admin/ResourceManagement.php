<?php

namespace App\Livewire\Admin;

use App\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;

class ResourceManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingId = null;
    
    // Form alanları
    public $name;
    public $publisher;
    public $description;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($resourceId = null)
    {
        if ($resourceId) {
            $resource = Resource::findOrFail($resourceId);
            $this->editingId = $resourceId;
            $this->name = $resource->name;
            $this->publisher = $resource->publisher;
            $this->description = $resource->description;
        } else {
            $this->resetForm();
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->publisher = '';
        $this->description = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($this->editingId) {
            $resource = Resource::findOrFail($this->editingId);
            $resource->update([
                'name' => $this->name,
                'publisher' => $this->publisher,
                'description' => $this->description,
            ]);
            
            session()->flash('message', 'Kaynak güncellendi.');
        } else {
            Resource::create([
                'name' => $this->name,
                'publisher' => $this->publisher,
                'description' => $this->description,
                'created_by_user_id' => auth()->id(),
                'is_admin_resource' => true,
            ]);
            
            session()->flash('message', 'Kaynak eklendi.');
        }

        $this->closeModal();
    }

    public function delete($resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        $resource->delete();
        
        session()->flash('message', 'Kaynak silindi.');
    }

    public function render()
    {
        $query = Resource::with(['createdBy', 'studentResources']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('publisher', 'like', '%' . $this->search . '%');
            });
        }

        $resources = $query->latest()->paginate(15);

        return view('livewire.admin.resource-management', [
            'resources' => $resources,
        ]);
    }
}


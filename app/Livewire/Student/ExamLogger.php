<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Field;
use Livewire\Component;
use Livewire\WithPagination;

class ExamLogger extends Component
{
    use WithPagination;

    public $showModal = false;
    public $exam_name;
    public $exam_type;
    public $field_id;
    public $course_id;
    public $correct_answers;
    public $wrong_answers;
    public $blank_answers;
    public $net_score = 0;
    public $exam_date;
    public $notes;
    
    public $fields = [];
    public $filteredCourses = [];
    public $examTypes = ['TYT', 'AYT', 'Deneme', 'Deneme-1', 'Deneme-2'];

    protected $rules = [
        'exam_name' => 'required|string|max:255',
        'exam_type' => 'nullable|string|max:255',
        'field_id' => 'nullable|exists:fields,id',
        'course_id' => 'nullable|exists:courses,id',
        'correct_answers' => 'required|integer|min:0',
        'wrong_answers' => 'required|integer|min:0',
        'blank_answers' => 'required|integer|min:0',
        'exam_date' => 'required|date',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->exam_date = now()->format('Y-m-d');
        $this->fields = Field::examCategories()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function updatedFieldId($value)
    {
        $this->course_id = null;
        $this->filteredCourses = [];

        if ($value) {
            $this->filteredCourses = Course::where('field_id', $value)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['correct_answers', 'wrong_answers'])) {
            $this->calculateNet();
        }
    }

    public function calculateNet()
    {
        $correct = (int) $this->correct_answers;
        $wrong = (int) $this->wrong_answers;
        
        // Net hesaplama: doğru - (yanlış / 4)
        $this->net_score = $correct - ($wrong / 4);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['exam_name', 'exam_type', 'field_id', 'course_id', 'correct_answers', 'wrong_answers', 'blank_answers', 'net_score', 'notes']);
        $this->filteredCourses = [];
        $this->exam_date = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        ExamResult::create([
            'student_id' => auth()->id(),
            'exam_name' => $this->exam_name,
            'exam_type' => $this->exam_type,
            'field_id' => $this->field_id,
            'course_id' => $this->course_id,
            'correct_answers' => $this->correct_answers,
            'wrong_answers' => $this->wrong_answers,
            'blank_answers' => $this->blank_answers,
            'net_score' => $this->net_score,
            'exam_date' => $this->exam_date,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Deneme sonucunuz başarıyla kaydedildi.');
        $this->closeModal();
    }

    public function delete($id)
    {
        ExamResult::where('student_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Kayıt silindi.');
    }

    public function render()
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        
        $examResults = ExamResult::where('student_id', auth()->id())
            ->with(['course', 'field'])
            ->latest('exam_date')
            ->paginate(10);

        $stats = [
            'total_exams' => ExamResult::where('student_id', auth()->id())->count(),
            'avg_net' => round(ExamResult::where('student_id', auth()->id())->avg('net_score'), 2),
            'best_net' => round(ExamResult::where('student_id', auth()->id())->max('net_score'), 2),
            'worst_net' => round(ExamResult::where('student_id', auth()->id())->min('net_score'), 2),
        ];

        // Stats by field
        $fieldStats = [];
        $fieldsData = Field::examCategories()->where('is_active', true)->get();
        foreach ($fieldsData as $field) {
            $fieldResults = ExamResult::where('student_id', auth()->id())
                ->where('field_id', $field->id)
                ->get();
            
            if ($fieldResults->count() > 0) {
                $fieldStats[$field->name] = [
                    'count' => $fieldResults->count(),
                    'avg_net' => round($fieldResults->avg('net_score'), 2),
                    'best_net' => round($fieldResults->max('net_score'), 2),
                ];
            }
        }

        return view('livewire.student.exam-logger', [
            'courses' => $courses,
            'examResults' => $examResults,
            'stats' => $stats,
            'fieldStats' => $fieldStats,
        ]);
    }
}

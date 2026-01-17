<?php

namespace App\Livewire\Coach;

use App\Models\AssignmentProgress;
use App\Models\StudentAssignment;
use App\Models\StudentResource;
use App\Models\User;
use Livewire\Component;

class StudentDetail extends Component
{
    public $studentId;
    public $student;
    public $expandedFields = [];
    public $expandedCourses = [];
    public $expandedTopics = [];
    
    // Not ekleme için
    public $editingNoteFor = null;
    public $noteText = '';

    public function mount($studentId)
    {
        $this->studentId = $studentId;
        $this->student = User::with(['questionLogs', 'examResults', 'studyLogs'])
            ->findOrFail($studentId);
        
        // Bu öğrenci bu koça ait mi kontrol et
        if (!auth()->user()->students()->where('users.id', $studentId)->exists()) {
            abort(403);
        }
    }

    public function toggleField($fieldName)
    {
        if (in_array($fieldName, $this->expandedFields)) {
            $this->expandedFields = array_diff($this->expandedFields, [$fieldName]);
        } else {
            $this->expandedFields[] = $fieldName;
        }
    }

    public function toggleCourse($courseId)
    {
        if (in_array($courseId, $this->expandedCourses)) {
            $this->expandedCourses = array_diff($this->expandedCourses, [$courseId]);
        } else {
            $this->expandedCourses[] = $courseId;
        }
    }

    public function toggleTopic($topicId)
    {
        if (in_array($topicId, $this->expandedTopics)) {
            $this->expandedTopics = array_diff($this->expandedTopics, [$topicId]);
        } else {
            $this->expandedTopics[] = $topicId;
        }
    }

    public function toggleProgress($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->findOrFail($assignmentId);

        $progress = AssignmentProgress::firstOrNew([
            'student_assignment_id' => $assignmentId,
        ]);

        // Sub topic ID'yi de ekliyoruz
        $progress->sub_topic_id = $assignment->sub_topic_id;
        $progress->is_completed = !$progress->is_completed;
        
        if ($progress->is_completed && !$progress->completed_at) {
            $progress->completed_at = now();
        } elseif (!$progress->is_completed) {
            $progress->completed_at = null;
        }

        $progress->save();

        session()->flash('message', $progress->is_completed ? 'Konu tamamlandı olarak işaretlendi.' : 'Tamamlandı işareti kaldırıldı.');
    }

    public function startEditingNote($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with('progress')
            ->findOrFail($assignmentId);

        $this->editingNoteFor = $assignmentId;
        $this->noteText = $assignment->progress?->notes ?? '';
    }

    public function saveNote($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->findOrFail($assignmentId);

        $progress = AssignmentProgress::firstOrCreate(
            [
                'student_assignment_id' => $assignmentId,
            ],
            [
                'sub_topic_id' => $assignment->sub_topic_id,
                'is_completed' => false,
            ]
        );

        $progress->notes = $this->noteText;
        $progress->save();

        $this->editingNoteFor = null;
        $this->noteText = '';

        session()->flash('message', 'Not kaydedildi.');
    }

    public function cancelNote()
    {
        $this->editingNoteFor = null;
        $this->noteText = '';
    }

    public function render()
    {
        // Öğrenciye atanan konular ve ilerleme durumu
        $assignments = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with(['course.field', 'topic', 'subTopic', 'progress'])
            ->get()
            ->groupBy('course.field.name');

        // Özet istatistikler
        $totalAssignments = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->count();

        $completedCount = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->whereHas('progress', function($q) {
                $q->where('is_completed', true);
            })
            ->count();

        $completionPercentage = $totalAssignments > 0 
            ? round(($completedCount / $totalAssignments) * 100, 1) 
            : 0;

        // Soru ve deneme istatistikleri
        $totalQuestions = $this->student->questionLogs->sum('total_questions');
        $correctAnswers = $this->student->questionLogs->sum('correct_answers');
        $totalExams = $this->student->examResults->count();
        $avgNet = $this->student->examResults->avg('net_score');

        // Öğrenciye atanan kaynaklar
        $studentResources = StudentResource::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with(['resource', 'course', 'field'])
            ->latest('assigned_at')
            ->get();

        // Group resources by field and course
        $groupedResources = [];
        foreach ($studentResources as $resource) {
            $fieldName = $resource->field ? $resource->field->name : 'Genel';
            $courseName = $resource->course ? $resource->course->name : 'Genel';
            
            if (!isset($groupedResources[$fieldName])) {
                $groupedResources[$fieldName] = [];
            }
            if (!isset($groupedResources[$fieldName][$courseName])) {
                $groupedResources[$fieldName][$courseName] = [];
            }
            $groupedResources[$fieldName][$courseName][] = $resource;
        }

        return view('livewire.coach.student-detail', [
            'assignments' => $assignments,
            'totalAssignments' => $totalAssignments,
            'completedCount' => $completedCount,
            'completionPercentage' => $completionPercentage,
            'totalQuestions' => $totalQuestions,
            'correctAnswers' => $correctAnswers,
            'totalExams' => $totalExams,
            'avgNet' => $avgNet,
            'studentResources' => $studentResources,
            'groupedResources' => $groupedResources,
        ]);
    }
}


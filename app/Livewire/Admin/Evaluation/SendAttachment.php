<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\Evaluation;
use App\Models\EvaluationAttachment;
use App\Models\EvaluationRating;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendAttachment extends Component
{
    use WithFileUploads;

    public $evaluations;
    public $attachment = [];
    public $activeTab = 1;
    public $hasEvaluationRating;

    public function listEvaluations()
    {
        $this->evaluations = Evaluation::with('evaluationItems')->get();
    }

    public function mount()
    {
        $user = User::where('id', auth()->user()->id)->where('police_id', auth()->user()->police_id)->first();

        $this->hasEvaluationRating = EvaluationRating::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$user || $this->hasEvaluationRating) {
            $currentMonth = Carbon::now()->month;

            if ($this->hasEvaluationRating) {
                $lastEvaluationMonth = $this->hasEvaluationRating?->created_at->month;

                if ($lastEvaluationMonth >= 1 && $lastEvaluationMonth <= 6) {
                    if ($currentMonth >= 1 && $currentMonth <= 6) {
                        $this->redirect('/admin/evaluation/send-attachment', navigate: true);
                        return;
                    }
                }

                if ($lastEvaluationMonth >= 7 && $lastEvaluationMonth <= 12) {
                    if ($currentMonth >= 7 && $currentMonth <= 12) {
                        $this->redirect('/admin/evaluation/send-attachment', navigate: true);
                        return;
                    }
                }
            }
        }
    }

    public function setActiveTab($tabIndex)
    {
        $this->activeTab = $tabIndex;
    }

    public function submitEvaluation()
    {

        foreach ($this->evaluations as $evaluation) {
            foreach ($evaluation->evaluationItems as $evaluationItem) {

                $existingAttachment = EvaluationAttachment::where('user_id', auth()->user()->id)
                    ->where('evaluation_item_id', $evaluationItem->id)
                    ->first();

                $attachmentPath = null;
                if (isset($this->attachment[$evaluationItem->id]) && $this->attachment[$evaluationItem->id]) {
                    $attachmentPath = $this->attachment[$evaluationItem->id]->store('uploaded-attachments', 'public');
                }

                if ($existingAttachment) {
                    $existingAttachment->update([
                        'attachment' => $attachmentPath ?: $existingAttachment->attachment,
                    ]);
                } else {
                    EvaluationAttachment::create([
                        'user_id' => auth()->user()->id,
                        'evaluation_item_id' => $evaluationItem->id,
                        'attachment' => $attachmentPath ?: null,
                    ]);
                }
            }
        }


        $this->dispatch('toastr', [
            'type'          =>          'success',
            'message'       =>          'Evaluation attachment sent successfully',
        ]);

        $this->reset();

        $this->redirect('/admin/evaluation/send-attachment', navigate: true);
    }
    public function render()
    {
        return view('livewire.admin.evaluation.send-attachment', [
            $this->listEvaluations()
        ]);
    }
}
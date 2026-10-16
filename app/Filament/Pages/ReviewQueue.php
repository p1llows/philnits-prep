<?php

namespace App\Filament\Pages;

use App\Models\Choice;
use App\Models\Question;
use App\Models\SourcePackage;
use App\Models\Topic;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\URL;

class ReviewQueue extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Review queue';

    protected static ?string $title = 'Review queue';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.review-queue';

    public function getSubheading(): ?string
    {
        return 'Verify AI-extracted questions, confirm answer keys, and approve syllabus topics.';
    }

    public ?int $selectedPackageId = null;

    public int $currentIndex = 0;

    public ?int $currentQuestionId = null;

    // Form editing state for extracted question
    public string $question_text = '';
    public ?string $code_block = null;
    public ?string $image_path = null;
    public ?string $correct_answer_code = null;
    public string $answer_source = 'model_suggestion';
    public ?string $field = null;
    public ?int $topic_id = null;
    public ?string $explanation = null;
    public string $explanation_status = 'none';
    public ?string $review_note = null;

    // Choices A-D text
    public string $choice_A = '';
    public string $choice_B = '';
    public string $choice_C = '';
    public string $choice_D = '';

    // Modal state for approving manual answer
    public bool $showManualConfirmModal = false;
    public bool $showNeedsFixModal = false;
    public string $needs_fix_note = '';

    public static function getNavigationBadge(): ?string
    {
        $count = Question::where('status', 'in_review')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public function mount(): void
    {
        $firstQuestion = Question::where('status', 'in_review')->orderBy('id')->first();
        if ($firstQuestion) {
            $this->loadQuestion($firstQuestion);
        }
    }

    public function updatedSelectedPackageId(): void
    {
        $this->currentIndex = 0;
        $q = $this->getCurrentQuery()->first();
        if ($q) {
            $this->loadQuestion($q);
        } else {
            $this->currentQuestionId = null;
        }
    }

    protected function getCurrentQuery()
    {
        $query = Question::where('status', 'in_review')->orderBy('id');
        if ($this->selectedPackageId) {
            $query->where('source_package_id', $this->selectedPackageId);
        }
        return $query;
    }

    public function getQuestionsCountProperty(): int
    {
        return $this->getCurrentQuery()->count();
    }

    public function loadQuestion(Question $question): void
    {
        $this->currentQuestionId = $question->id;
        $this->question_text = $question->question_text ?? '';
        $this->code_block = $question->code_block;
        $this->image_path = $question->image_path;
        $this->correct_answer_code = $question->correct_answer_code;
        $this->answer_source = $question->answer_source ?? 'model_suggestion';
        $this->topic_id = $question->topic_id;
        $this->field = $question->topic?->field ?? 'technology';
        $this->explanation = $question->explanation;
        $this->explanation_status = $question->explanation_status ?? 'none';
        $this->review_note = $question->review_note;

        // Load choices
        $choices = $question->choices()->get()->keyBy('code');
        $this->choice_A = $choices->get('A')?->text ?? '';
        $this->choice_B = $choices->get('B')?->text ?? '';
        $this->choice_C = $choices->get('C')?->text ?? '';
        $this->choice_D = $choices->get('D')?->text ?? '';
    }

    public function nextQuestion(): void
    {
        $total = $this->questionsCount;
        if ($this->currentIndex < $total - 1) {
            $this->currentIndex++;
            $q = $this->getCurrentQuery()->skip($this->currentIndex)->first();
            if ($q) {
                $this->loadQuestion($q);
            }
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $q = $this->getCurrentQuery()->skip($this->currentIndex)->first();
            if ($q) {
                $this->loadQuestion($q);
            }
        }
    }

    public function getCurrentQuestion(): ?Question
    {
        if (!$this->currentQuestionId) {
            return null;
        }
        return Question::with(['sourcePackage', 'topic', 'choices'])->find($this->currentQuestionId);
    }

    public function getPdfPreviewUrl(): ?string
    {
        $q = $this->getCurrentQuestion();
        if (!$q || !$q->sourcePackage || !$q->sourcePackage->paper_path) {
            return null;
        }

        $baseUrl = URL::temporarySignedRoute(
            'admin.pdf.preview',
            now()->addHours(2),
            ['sourcePackage' => $q->sourcePackage->id]
        );

        if ($q->source_page) {
            $baseUrl .= "#page={$q->source_page}";
        }

        return $baseUrl;
    }

    public function getPipelineCounts(): array
    {
        return [
            'uploaded' => SourcePackage::where('status', 'uploaded')->count(),
            'parsed' => Question::where('status', 'draft')->count(),
            'in_review' => Question::where('status', 'in_review')->count(),
            'published' => Question::where('status', 'published')->count(),
        ];
    }

    public function saveDraft(): void
    {
        $q = $this->getCurrentQuestion();
        if (!$q) {
            return;
        }

        $q->update([
            'question_text' => $this->question_text,
            'code_block' => $this->code_block,
            'correct_answer_code' => $this->correct_answer_code,
            'answer_source' => $this->answer_source,
            'topic_id' => $this->topic_id,
            'explanation' => $this->explanation,
            'explanation_status' => $this->explanation_status,
        ]);

        $this->syncChoices($q);

        Notification::make()->title('Draft saved')->success()->send();
    }

    protected function syncChoices(Question $question): void
    {
        $choiceMap = [
            'A' => $this->choice_A,
            'B' => $this->choice_B,
            'C' => $this->choice_C,
            'D' => $this->choice_D,
        ];

        $order = 1;
        foreach ($choiceMap as $code => $text) {
            Choice::updateOrCreate(
                ['question_id' => $question->id, 'code' => $code],
                ['text' => $text, 'display_order' => $order++]
            );
        }
    }

    public function approve(): void
    {
        $q = $this->getCurrentQuestion();
        if (!$q) {
            return;
        }

        // Validate approval guards:
        // 1. Correct choice selected
        if (empty($this->correct_answer_code) || !in_array($this->correct_answer_code, ['A', 'B', 'C', 'D'])) {
            Notification::make()->title('Approval blocked')->body('A correct choice (A, B, C, or D) must be selected.')->danger()->send();
            return;
        }

        // 2. Field and topic are set
        if (empty($this->topic_id)) {
            Notification::make()->title('Approval blocked')->body('A syllabus topic and field must be assigned.')->danger()->send();
            return;
        }

        // 3. model_suggestion can never be approved
        if ($this->answer_source === 'model_suggestion') {
            Notification::make()
                ->title('Approval blocked')
                ->body('Model suggestion answers cannot be approved. Confirm against the official key or verify manually.')
                ->danger()
                ->send();
            return;
        }

        // 4. Manual requires confirmation modal
        if ($this->answer_source === 'manual') {
            $this->showManualConfirmModal = true;
            return;
        }

        $this->processApproval($q);
    }

    public function confirmManualApproval(): void
    {
        $this->showManualConfirmModal = false;
        $q = $this->getCurrentQuestion();
        if ($q) {
            $this->processApproval($q);
        }
    }

    protected function processApproval(Question $q): void
    {
        $this->syncChoices($q);

        $explanationStatus = !empty($this->explanation) ? 'approved' : 'none';

        $q->update([
            'question_text' => $this->question_text,
            'code_block' => $this->code_block,
            'correct_answer_code' => $this->correct_answer_code,
            'answer_source' => $this->answer_source,
            'topic_id' => $this->topic_id,
            'explanation' => $this->explanation,
            'explanation_status' => $explanationStatus,
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Services\Audit::log('question.approved', $q, [
            'question_id' => $q->id,
            'correct_answer_code' => $this->correct_answer_code,
            'topic_id' => $this->topic_id,
        ]);

        Notification::make()->title('Question approved')->success()->send();

        $this->loadNextOrReload();
    }

    public function openNeedsFixModal(): void
    {
        $this->needs_fix_note = $this->review_note ?? '';
        $this->showNeedsFixModal = true;
    }

    public function submitNeedsFix(): void
    {
        $q = $this->getCurrentQuestion();
        if (!$q) {
            return;
        }

        $q->update([
            'status' => 'in_review',
            'review_note' => $this->needs_fix_note,
        ]);

        \App\Services\Audit::log('question.rejected', $q, [
            'question_id' => $q->id,
            'note' => $this->needs_fix_note,
        ]);

        $this->showNeedsFixModal = false;
        Notification::make()->title('Question marked as needs fix')->warning()->send();

        $this->loadNextOrReload();
    }

    public function reject(): void
    {
        $q = $this->getCurrentQuestion();
        if (!$q) {
            return;
        }

        $q->update([
            'status' => 'rejected',
        ]);

        Notification::make()->title('Question rejected')->danger()->send();

        $this->loadNextOrReload();
    }

    protected function loadNextOrReload(): void
    {
        $q = $this->getCurrentQuery()->first();
        if ($q) {
            $this->loadQuestion($q);
        } else {
            $this->currentQuestionId = null;
        }
    }
}

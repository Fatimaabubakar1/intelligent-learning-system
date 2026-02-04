@extends('layouts.app')

@section('title', 'Create New Lesson')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Lesson</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('lessons.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Lesson Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="col-md-6">
                                <label for="language" class="form-label">Language *</label>
                                <select class="form-select" id="language" name="language" required>
                                    <option value="">Select Language</option>
                                    <option value="hausa">Hausa</option>
                                    <option value="yoruba">Yoruba</option>
                                    <option value="igbo">Igbo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="level" class="form-label">Level *</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="duration" class="form-label">Duration (minutes) *</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="1" value="15" required>
                            </div>

                            <div class="col-md-3">
                                <label for="order" class="form-label">Order *</label>
                                <input type="number" class="form-control" id="order" name="order" min="0" value="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <!-- Dynamic Content Sections -->
                        <div id="content-sections">
                            <div class="card mb-3 content-section">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Content Section 1</h5>
                                    <button type="button" class="btn btn-sm btn-danger remove-section">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Section Title</label>
                                            <input type="text" class="form-control section-title" name="content[0][title]" placeholder="e.g., Introduction">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Section Type</label>
                                            <select class="form-select section-type" name="content[0][type]">
                                                <option value="text">Text</option>
                                                <option value="list">List</option>
                                                <option value="example">Example</option>
                                                <option value="vocabulary">Vocabulary</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="section-content">
                                        <div class="mb-3">
                                            <label class="form-label">Content</label>
                                            <textarea class="form-control section-text" name="content[0][content]" rows="3" placeholder="Enter text content"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="add-section" class="btn btn-secondary">
                                <i class="fas fa-plus"></i> Add Another Section
                            </button>
                        </div>

                        <!-- Exercise Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Practice Exercise (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Exercise Type</label>
                                        <select class="form-select" id="exercise-type" name="exercise[type]">
                                            <option value="">No Exercise</option>
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="fill_blank">Fill in the Blank</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="exercise-content" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Question</label>
                                        <input type="text" class="form-control" id="exercise-question" name="exercise[question]" placeholder="Enter question">
                                    </div>

                                    <div id="multiple-choice-options" style="display: none;">
                                        <label class="form-label">Options (one per line)</label>
                                        <textarea class="form-control" id="exercise-options" name="exercise[options]" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3&#10;Option 4"></textarea>
                                    </div>

                                    <div id="fill-blank-content" style="display: none;">
                                        <label class="form-label">Sentence with Blank</label>
                                        <input type="text" class="form-control" id="exercise-sentence" name="exercise[sentence]" placeholder="e.g., The cat sat on the ___">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active Lesson</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lessons.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Lesson</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let sectionCount = 1;

    // Add new content section
    document.getElementById('add-section').addEventListener('click', function() {
        const template = `
        <div class="card mb-3 content-section">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Content Section ${sectionCount + 1}</h5>
                <button type="button" class="btn btn-sm btn-danger remove-section">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Section Title</label>
                        <input type="text" class="form-control section-title" name="content[${sectionCount}][title]" placeholder="e.g., Introduction">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Section Type</label>
                        <select class="form-select section-type" name="content[${sectionCount}][type]">
                            <option value="text">Text</option>
                            <option value="list">List</option>
                            <option value="example">Example</option>
                            <option value="vocabulary">Vocabulary</option>
                        </select>
                    </div>
                </div>

                <div class="section-content">
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea class="form-control section-text" name="content[${sectionCount}][content]" rows="3" placeholder="Enter text content"></textarea>
                    </div>
                </div>
            </div>
        </div>`;

        document.getElementById('content-sections').insertAdjacentHTML('beforeend', template);
        sectionCount++;
    });

    // Remove content section
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-section') ||
            e.target.parentElement.classList.contains('remove-section')) {
            const btn = e.target.classList.contains('remove-section') ? e.target : e.target.parentElement;
            btn.closest('.content-section').remove();
            // Reindex sections
            updateSectionIndexes();
        }
    });

    // Handle section type changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('section-type')) {
            const section = e.target.closest('.content-section');
            const type = e.target.value;
            const contentDiv = section.querySelector('.section-content');

            let contentHTML = '';

            switch(type) {
                case 'text':
                    contentHTML = `
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control section-text" name="content[${getSectionIndex(section)}][content]" rows="3" placeholder="Enter text content"></textarea>
                        </div>`;
                    break;

                case 'list':
                    contentHTML = `
                        <div class="mb-3">
                            <label class="form-label">List Items (one per line)</label>
                            <textarea class="form-control section-items" name="content[${getSectionIndex(section)}][items]" rows="4" placeholder="Item 1&#10;Item 2&#10;Item 3"></textarea>
                        </div>`;
                    break;

                case 'example':
                    contentHTML = `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Sentence</label>
                                <input type="text" class="form-control section-sentence" name="content[${getSectionIndex(section)}][sentence]" placeholder="Enter sentence">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Translation</label>
                                <input type="text" class="form-control section-translation" name="content[${getSectionIndex(section)}][translation]" placeholder="Enter translation">
                            </div>
                        </div>`;
                    break;

                case 'vocabulary':
                    contentHTML = `
                        <div class="mb-3">
                            <label class="form-label">Vocabulary (JSON format)</label>
                            <textarea class="form-control section-words" name="content[${getSectionIndex(section)}][words]" rows="4" placeholder='[{"word": "hello", "meaning": "greeting", "pos": "NOUN"}]'></textarea>
                            <small class="text-muted">Enter as JSON array of word objects</small>
                        </div>`;
                    break;
            }

            contentDiv.innerHTML = contentHTML;
        }
    });

    // Handle exercise type changes
    const exerciseType = document.getElementById('exercise-type');
    const exerciseContent = document.getElementById('exercise-content');
    const multipleChoiceOptions = document.getElementById('multiple-choice-options');
    const fillBlankContent = document.getElementById('fill-blank-content');

    exerciseType.addEventListener('change', function() {
        if (this.value) {
            exerciseContent.style.display = 'block';
            multipleChoiceOptions.style.display = this.value === 'multiple_choice' ? 'block' : 'none';
            fillBlankContent.style.display = this.value === 'fill_blank' ? 'block' : 'none';
        } else {
            exerciseContent.style.display = 'none';
        }
    });

    function getSectionIndex(section) {
        const sections = document.querySelectorAll('.content-section');
        return Array.from(sections).indexOf(section);
    }

    function updateSectionIndexes() {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach((section, index) => {
            // Update section header
            section.querySelector('.card-header h5').textContent = `Content Section ${index + 1}`;

            // Update input names
            const titleInput = section.querySelector('.section-title');
            const typeSelect = section.querySelector('.section-type');

            if (titleInput) titleInput.name = `content[${index}][title]`;
            if (typeSelect) typeSelect.name = `content[${index}][type]`;

            // Update content fields based on type
            const currentType = typeSelect ? typeSelect.value : 'text';
            let contentField;

            switch(currentType) {
                case 'text':
                    contentField = section.querySelector('.section-text');
                    if (contentField) contentField.name = `content[${index}][content]`;
                    break;
                case 'list':
                    contentField = section.querySelector('.section-items');
                    if (contentField) contentField.name = `content[${index}][items]`;
                    break;
                case 'example':
                    const sentenceField = section.querySelector('.section-sentence');
                    const translationField = section.querySelector('.section-translation');
                    if (sentenceField) sentenceField.name = `content[${index}][sentence]`;
                    if (translationField) translationField.name = `content[${index}][translation]`;
                    break;
                case 'vocabulary':
                    contentField = section.querySelector('.section-words');
                    if (contentField) contentField.name = `content[${index}][words]`;
                    break;
            }
        });
        sectionCount = sections.length;
    }
});
</script>
@endsection

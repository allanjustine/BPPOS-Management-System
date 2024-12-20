<div>
    <div class="container mt-5">
        <div class="mb-5">
            <a href="/users/evaluation/send-attachment" wire:navigate
                class="btn btn-link text-decoration-none border btn-sm bg-dark-subtle rounded text-dark mt-3"><i
                    class="far fa-arrow-left"></i> Back</a>
        </div>

        <!-- Progress Indicator -->
        <h3 class="text-center"><strong>Hello, </strong> <u>{{ auth()->user()->last_name }}{{
                auth()->user()->last_name ? ',' : '' }} {{
                auth()->user()->first_name }}</u></h3>
        <div class="progress-container mb-4">
            <span class="step-indicator {{ $activeTab === 1 || $activeTab === 2 || $activeTab === 3 ? 'active' : '' }}"
                id="step1-indicator" wire:click="setActiveTab(1)" style="cursor: pointer;">
                <span wire:loading.remove wire:target='setActiveTab(1)'>1</span><span wire:loading
                    wire:target='setActiveTab(1)'><span class="spinner-border spinner-border-sm"></span></span>
            </span>
            <div class="progress-bar-container">
                <div class="progress-bar {{ $activeTab === 2 || $activeTab === 3 ? 'active' : '' }}"
                    id="progress-bar-1"></div>
            </div>
            <span class="step-indicator {{ $activeTab === 2 || $activeTab === 3 ? 'active' : '' }}" id="step2-indicator"
                wire:click="setActiveTab(2)" style="cursor: pointer;">
                <span wire:loading.remove wire:target='setActiveTab(2)'>2</span><span wire:loading
                    wire:target='setActiveTab(2)'><span class="spinner-border spinner-border-sm"></span></span>
            </span>
            <div class="progress-bar-container">
                <div class="progress-bar {{ $activeTab === 3 ? 'active' : '' }}" id="progress-bar-2"></div>
            </div>
            <span class="step-indicator {{ $activeTab === 3 ? 'active' : '' }}" id="step3-indicator"
                wire:click="setActiveTab(3)" style="cursor: pointer;">
                <span wire:loading.remove wire:target='setActiveTab(3)'>3</span><span wire:loading
                    wire:target='setActiveTab(3)'><span class="spinner-border spinner-border-sm"></span></span>
            </span>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="stepForm" wire:submit.prevent='submitEvaluation'>
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Step 1 -->
                        <div class="tab-pane fade {{ $activeTab === 1 ? 'show active' : '' }}" id="step1"
                            role="tabpanel" aria-labelledby="step1-tab">
                            @forelse($evaluations->where('title', 'Output') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimensions</h3>
                                        {{ $evaluation->title }}({{ $evaluation->sub_title }} points)
                                    </strong></label>
                                <table class="table table-hover">
                                    <thead>
                                        <td>Performance Indications</td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @forelse ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>{{ $item->performance_indications }}</td>
                                            <td>
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-center">
                                                    No evaluation items yet
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @empty
                            <div class="text-center">
                                <h3>No evaluations founded</h3>
                            </div>
                            @endforelse
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)"
                                wire:click="setActiveTab(2)"><span wire:loading.remove
                                    wire:target='setActiveTab(2)'>Next</span><span wire:loading
                                    wire:target='setActiveTab(2)'><span
                                        class="spinner-border spinner-border-sm"></span></span></button>
                        </div>
                        <!-- Step 2 -->
                        <div class="tab-pane fade {{ $activeTab === 2 ? 'show active' : '' }}" id="step2"
                            role="tabpanel" aria-labelledby="step2-tab">
                            @forelse($evaluations->where('title', 'Job Knowledge') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimensions</h3>
                                        {{ $evaluation->title }}({{ $evaluation->sub_title }} points)
                                    </strong></label>
                                <table class="table table-hover">
                                    <thead>
                                        <td>
                                            Performance Indications
                                        </td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @forelse ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>
                                                <div
                                                    style="min-width: 500px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $item->performance_indications }}</div>
                                            </td>

                                            <td>
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-center">
                                                    No evaluation items yet
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @empty
                            <div class="text-center">
                                <h3>No evaluations founded</h3>
                            </div>
                            @endforelse
                            @forelse($evaluations->where('title', 'Work Management') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimensions</h3>
                                        {{ $evaluation->title }}({{ $evaluation->sub_title }} points)
                                    </strong></label>
                                <table class="table table-hover">
                                    <thead>
                                        <td>Performance Indications</td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @forelse ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>
                                                <div
                                                    style="min-width: 500px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $item->performance_indications }}</div>
                                            </td>

                                            <td>
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-center">
                                                    No evaluation items yet
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @empty
                            <div class="text-center">
                                <h3>No evaluations founded</h3>
                            </div>
                            @endforelse
                            @forelse($evaluations->where('title', 'Interpersonal Relationship') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimensions</h3>
                                        {{ $evaluation->title }}({{ $evaluation->sub_title }} points)
                                    </strong></label>
                                <table class="table table-hover">
                                    <thead>
                                        <td>Performance Indications</td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @forelse ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>
                                                <div
                                                    style="min-width: 500px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $item->performance_indications }}</div>
                                            </td>

                                            <td>
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-center">
                                                    No evaluation items yet
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @empty
                            <div class="text-center">
                                <h3>No evaluations founded</h3>
                            </div>
                            @endforelse
                            @forelse($evaluations->where('title', 'Concern for the Organization') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimensions</h3>
                                        {{ $evaluation->title }}({{ $evaluation->sub_title }} points)
                                    </strong></label>
                                <table class="table table-hover">
                                    <thead>
                                        <td>Performance Indications</td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @forelse ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>
                                                <div
                                                    style="min-width: 500px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $item->performance_indications }}</div>
                                            </td>

                                            <td>
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-center">
                                                    No evaluation items yet
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @empty
                            <div class="text-center">
                                <h3>No evaluations founded</h3>
                            </div>
                            @endforelse
                            <button type="button" class="btn btn-secondary" onclick="prevStep(1)"
                                wire:click="setActiveTab(1)">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)"
                                wire:click="setActiveTab(3)"><span wire:loading.remove
                                    wire:target='setActiveTab(3)'>Next</span><span wire:loading
                                    wire:target='setActiveTab(3)'><span
                                        class="spinner-border spinner-border-sm"></span></span></button>
                        </div>
                        <!-- Step 3 -->
                        <div class="tab-pane fade {{ $activeTab === 3 ? 'show active' : '' }}" id="step3"
                            role="tabpanel" aria-labelledby="step3-tab">
                            @forelse ($evaluations->where('title', 'Personal Qualities') as $evaluation)
                            <div class="mb-3">
                                <label for="firstName" class="form-label"><strong>
                                        <h3>Dimension</h3>{{ $evaluation->title }}({{ $evaluation->sub_title }}
                                        points)
                                    </strong></label>
                                <table class="table" class="text-center align-middle">
                                    <thead>
                                        <td colspan="4">Performance Indications</td>
                                        <td>Submit Attachment</td>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluation->evaluationItems as $index => $item)
                                        <tr wire:key='{{ $item->id }}'>

                                            <td>{{ $item->performance_indications }}</td>
                                            <td>x</td>
                                            <td>{{ $item->performance_indications }}</td>
                                            <td>x</td>

                                            <td rowspan="6">
                                                @php
                                                $uploadedAttachment =
                                                $item->evaluationAttachments->where('evaluation_item_id',
                                                $item->id)->where('user_id', Auth::user()->id)->first();
                                                @endphp

                                                @if($uploadedAttachment && $uploadedAttachment->attachment !== null)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ Storage::url($uploadedAttachment->attachment) }}"
                                                        alt="" width="40" height="40">
                                                    <button type="button"
                                                        wire:confirm="Are you sure you want to remove this attachment? This action cannot be undone."
                                                        class="btn btn-link text-dark"
                                                        wire:click="removeImage({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                @if(isset($attachment[$item->id]))
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <img src="{{ $attachment[$item->id]->temporaryUrl() }}" alt=""
                                                        width="40" height="40">
                                                    <button type="button" class="btn btn-link text-dark"
                                                        wire:click="removeTempUrl({{ $item->id }})"><i
                                                            class="far fa-x"></i></button>
                                                </div>
                                                @else
                                                <input wire:loading.remove wire:target='attachment.{{ $item->id }}'
                                                    accept="image/*" type="file"
                                                    wire:model.live.debounce.10ms='attachment.{{ $item->id }}'
                                                    placeholder="Attachment">
                                                <span wire:loading wire:target='attachment.{{ $item->id }}'
                                                    class="spinner-border spinner-border-sm"></span>
                                                @endif
                                                @error('attachment.'.$item->id)
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </td>

                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>Morally Upright</td>
                                            <td>x</td>
                                            <td>Civic-Minded</td>
                                            <td>x</td>
                                        </tr>
                                        <tr>
                                            <td>Honest</td>
                                            <td>x</td>
                                            <td>Responsible</td>
                                            <td>x</td>
                                        </tr>
                                        <tr>
                                            <td>Well-groomed</td>
                                            <td>x</td>
                                            <td>Discipline</td>
                                            <td>x</td>
                                        </tr>
                                        <tr>
                                            <td>Fair and Just</td>
                                            <td>x</td>
                                            <td>Courteous/tactful</td>
                                            <td>x</td>
                                        </tr>
                                        <tr>
                                            <td>Loyal to the organization</td>
                                            <td>x</td>
                                            <td>Initiates Positive Action</td>
                                            <td>x</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @empty

                            @endforelse
                            <button type="button" class="btn btn-secondary" onclick="prevStep(2)"
                                wire:click="setActiveTab(2)">Previous</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:target='submitEvaluation' wire:loading.remove>Submit</span>
                                <span wire:target='submitEvaluation' wire:loading><span
                                        class="spinner-border spinner-border-sm"></span> Please wait...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        .step-indicator {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            background-color: #e9ecef;
            color: #000;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
        }

        .step-indicator.active {
            background-color: #007bff;
            color: #fff;
        }

        .progress-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .progress-bar-container {
            flex: 1;
            margin: 0 10px;
        }

        .progress-bar {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            width: 100%;
        }

        .progress-bar.active {
            background-color: #007bff;
        }

        /* Center text horizontally and vertically in all cells */
        table td,
        table th {
            text-align: center;
            vertical-align: middle;
        }

        /* Exclude the first td in the thead from centering */
        thead td:first-child,
        thead th:first-child {
            text-align: left;
            /* or any other alignment */
            /* vertical-align: top; */
            /* or any other alignment */
        }

        tbody td:first-child,
        tbody th:first-child {
            text-align: left;
            /* or any other alignment */
            vertical-align: top;
            /* or any other alignment */
        }

        .number-input {
            border: none;
            text-align: center;
        }
    </style>


    <script>
        document.addEventListener('livewire:navigated', ()=>{

            @this.on('toastr', (event) => {
                const data=event
                toastr[data[0].type](data[0].message, '', {
                closeButton: true,
                "progressBar": true,
                timeOut: 5000,
                });
            })
        })
    </script>

    <script>
        document.addEventListener('livewire:navigated', function() {
            const inputs = document.querySelectorAll('.number-input');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Enforce integer range 1 to 5
                    let value = parseInt(this.value, 10);
                    if (isNaN(value) || value < 1 || value > 5) {
                        this.value = ''; // Clear the input if invalid
                    } else {
                        this.value = value; // Ensure the value is an integer
                    }
                });

                input.addEventListener('keydown', function(e) {
                    // Prevent special characters and non-numeric input
                    const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter'];
                    if (!allowedKeys.includes(e.key) && (e.key < '0' || e.key > '9')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</div>

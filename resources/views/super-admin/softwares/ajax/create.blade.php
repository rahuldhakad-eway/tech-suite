<div class="row">
    <div class="col-sm-12">
        <x-form id="save-software-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-3 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('superadmin.software-category.create')</h4>
                <div class="row px-3">
                    <div class="col-lg-8 col-md-6">
                    <x-forms.select fieldId="currency_id"
                                                :fieldLabel="__('modules.accountSettings.defaultCurrency')"
                                                fieldName="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="col-lg-8 col-md-6">
                    <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2"
                                              :fieldLabel="__('modules.accountSettings.companyAddress')"
                                              fieldName="description"
                                              fieldRequired="true"
                                              fieldId="address" fieldPlaceholder="e.g. Rocket Road">
                            </x-forms.textarea>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="col-lg-4 col-md-6">
                    <x-forms.file allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                                      :fieldLabel="__('modules.accountSettings.companyLogo')" fieldName="logo"
                                      fieldId="logo"
                                      fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')"/>
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary class="mr-3" id="save-software-form" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('superadmin.softwares.index')"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>


<script>

    $(document).ready(function () {
        $('#save-software-form').click(function () {
            $.easyAjax({
                url: "{{ route('superadmin.softwares.store') }}",
                container: '#save-software-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-software-form",
                file: true,
                data: $('#save-software-data-form').serialize(),
            });
        });

        init(RIGHT_MODAL);
    });


</script>

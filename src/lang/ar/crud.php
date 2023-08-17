<?php
    return [
        'add'=>'اضافة ',
        'preview'=>'مراجعة',
        'edit'=>'تعديل',
        'delete'=>'حذف',
        'warning'=>'تحذير',
        'delete_confirm'=>'حقا تريد الحذف؟؟؟',
        'cancel'=>'الغاء',
            'save_action_save_and_new'         => 'Save and new item',
    'save_action_save_and_edit'        => 'Save and edit this item',
    'save_action_save_and_back'        => 'Save and back',
    'save_action_save_and_preview'     => 'Save and preview',
    'save_action_changed_notification' => 'Default behaviour after saving has been changed.',
    'error_saving'=>'لم يتم الحفظ',
    // Create form
    'add'                 => 'اضافة',
    'back_to_all'         => 'العودة لكل ',
    'add_a_new'           => 'اضافة جديد ',

    // Edit form
    'edit'                 => 'تعديل',
    'save'                 => 'حفظ',

    // Translatable models
    'edit_translations' => 'Translation',
    'language'          => 'Language',

    // CRUD table view
    'all'                       => 'All ',
    'in_the_database'           => 'in the database',
    'list'                      => 'List',
    'reset'                     => 'اعادة',
    'actions'                   => 'الاحداث',
    'preview'                   => 'Preview',
    'delete'                    => 'حذف',
    'admin'                     => 'الادارة',
    'details_row'               => 'This is the details row. Modify as you please.',
    'details_row_loading_error' => 'خطأ فى تحميل البيانات من فضلك حاول مرة اخرى.',
    'clone'                     => 'استنساخ',
    'clone_success'             => '<strong>Entry cloned</strong><br>A new entry has been added, with the same information as this one.',
    'clone_failure'             => '<strong>Cloning failed</strong><br>The new entry could not be created. Please try again.',

    // Confirmation messages and bubbles
    'delete_confirm'                              => 'Are you sure you want to delete this item?',
    'delete_confirmation_title'                   => 'Item Deleted',
    'delete_confirmation_message'                 => 'The item has been deleted successfully.',
    'delete_confirmation_not_title'               => 'NOT deleted',
    'delete_confirmation_not_message'             => "There's been an error. Your item might not have been deleted.",
    'delete_confirmation_not_deleted_title'       => 'Not deleted',
    'delete_confirmation_not_deleted_message'     => 'Nothing happened. Your item is safe.',

    // Bulk actions
    'bulk_no_entries_selected_title'   => 'لم تختر سجلات',
    'bulk_no_entries_selected_message' => 'من فضلك اختر سجل .',

    // Bulk delete
    'bulk_delete_are_you_sure'   => 'Are you sure you want to delete these :number entries?',
    'bulk_delete_sucess_title'   => 'Entries deleted',
    'bulk_delete_sucess_message' => ' items have been deleted',
    'bulk_delete_error_title'    => 'Delete failed',
    'bulk_delete_error_message'  => 'One or more items could not be deleted',

    // Bulk clone
    'bulk_clone_are_you_sure'   => 'هل انت متأكد من استنساخ :number سجلات?',
    'bulk_clone_sucess_title'   => 'تم استنساخ السجلات',
    'bulk_clone_sucess_message' => ' تم استنساخ  البند.',
    'bulk_clone_error_title'    => 'فشل فى الاستنساخ',
    'bulk_clone_error_message'  => 'لا يمكن انشاء سجل او اكثر... فضلا حاول مرة اخرى.',

    // Ajax errors
    'ajax_error_title' => 'خطأ',
    'ajax_error_text'  => 'خطأ فى تحميل البيانات، من فضلك حاول مرة اخرى.',

    // DataTables translation
    'emptyTable'     => 'لا يوجد سجلات فى الجدول',
    'info'           => 'عرض (_START_) الى (_END_) من (_TOTAL_) سجلات',
    'infoEmpty'      => 'لا يوجد سجلات',
    'infoFiltered'   => '(مرشح من (_MAX_) اجمالى السجلات)',
    'infoPostFix'    => '.',
    'thousands'      => ',',
    'lengthMenu'     => '_MENU_ سجل فى الصفحة',
    'loadingRecords' => 'جارى التحميل...',
    'processing'     => 'جارى المعالجة...',
    'search'         => 'بحث',
    'zeroRecords'    => 'لم نجد سجلات مشابهة',
    'paginate'       => [
        'first'    => 'الاول',
        'last'     => 'الاخر',
        'next'     => 'التالى',
        'previous' => 'السابق',
    ],
    'aria' => [
        'sortAscending'  => ': تفعيل لفرز العمود تصاعديًا',
        'sortDescending' => ': تفعيل لفرز العمود تنازليًا',
    ],
    'export' => [
        'export'            => 'تصدير',
        'copy'              => 'نسخ',
        'excel'             => 'اكسل',
        'csv'               => 'CSV',
        'pdf'               => 'PDF',
        'print'             => 'طباعة',
        'column_visibility' => 'رؤية الاعمدة',
    ],

    // global crud - errors
    'unauthorized_access' => 'Unauthorized access - you do not have the necessary permissions to see this page.',
    'please_fix'          => 'Please fix the following errors:',

    // global crud - success / error notification bubbles
    'insert_success' => 'The item has been added successfully.',
    'update_success' => 'The item has been modified successfully.',

    // CRUD reorder view
    'reorder'                      => 'اعادة ترتيب',
    'reorder_text'                 => 'استخدم السحب والافلات للترتيب.',
    'reorder_success_title'        => 'تم',
    'reorder_success_message'      => 'تم حفظ الترتيب.',
    'reorder_error_title'          => 'خطأ',
    'reorder_error_message'        => 'لم يتم حفظ الترتيب.',

    // CRUD yes/no
    'yes' => 'Yes',
    'no'  => 'No',

    // CRUD filters navbar view
    'filters'        => 'Filters',
    'toggle_filters' => 'Toggle filters',
    'remove_filters' => 'Remove filters',
    'apply' => 'Apply',

    //filters language strings
    'today' => 'Today',
    'yesterday' => 'Yesterday',
    'last_7_days' => 'Last 7 Days',
    'last_30_days' => 'Last 30 Days',
    'this_month' => 'This Month',
    'last_month' => 'Last Month',
    'custom_range' => 'Custom Range',
    'weekLabel' => 'W',

    // Fields
    'browse_uploads'            => 'Browse uploads',
    'select_all'                => 'Select All',
    'select_files'              => 'Select files',
    'select_file'               => 'Select file',
    'clear'                     => 'Clear',
    'page_link'                 => 'Page link',
    'page_link_placeholder'     => 'http://example.com/your-desired-page',
    'internal_link'             => 'Internal link',
    'internal_link_placeholder' => 'Internal slug. Ex: \'admin/page\' (no quotes) for \':url\'',
    'external_link'             => 'External link',
    'choose_file'               => 'Choose file',
    'new_item'                  => 'New Item',
    'select_entry'              => 'Select an entry',
    'pivot_selector_required_validation_message'=>'pivot_selector_required_validation_message',
    'select_entries'            => 'Select entries',

    //Table field
    'table_cant_add'    => 'Cannot add new :entity',
    'table_max_reached' => 'Maximum number of :max reached',

    // File manager
    'file_manager' => 'File Manager',

    // InlineCreateOperation
    'related_entry_created_success' => 'Related entry has been created and selected.',
    'related_entry_created_error' => 'Could not create related entry.',
    ];


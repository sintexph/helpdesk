<?php

Auth::routes();

Route::get('/','HomeController@index');

Route::post('preview-generate','LinkPreviewController@get_preview')->name('preview.generate');

Route::prefix('utility')->name('.utility')->group(function(){

    Route::prefix('suggestions')->name('.suggestions')->group(function(){

        //Route::get('sender_name', 'UtilityController@suggestion_sender_name')->name('.sender_name');
        //Route::get('sender_email', 'UtilityController@suggestion_sender_email')->name('.sender_email');
        

        Route::name('.canned')->group(function(){
            Route::get('canned-solutions','UtilityController@canned_solutions')->name('.solutions');
            
        });


    });


    Route::get('find-user','UtilityController@find_user')->name('.find_user'); 
    Route::post('factories','UtilityController@factories')->name('.factories');
    Route::post('categories','UtilityController@categories')->name('.categories');
    Route::post('get-users','UtilityController@get_users')->name('.get_users');
    Route::post('get-auth-user','UtilityController@get_auth_user')->name('.get_auth_user');
    Route::post('applications','UtilityController@get_applications')->name('.application');
    Route::post('unclosed_tickets','UtilityController@unclosed_tickets')->name('.unclosed_tickets');

    Route::post('setting','UtilityController@setting')->name('.setting');
    Route::post('canned-solution/{id}','UtilityController@canned_solution')->name('.solution');
    Route::post('projects','UtilityController@projects')->name('.projects');
    Route::post('current-date','UtilityController@current_date')->name('.current_date');

});


Route::prefix('profile')->name('profile')->group(function(){

    Route::get('/{username}','ProfileController@index');
    Route::patch('update', 'ProfileController@update')->name('.approve');

});

Route::prefix('approval')->name('approval')->group(function(){
    Route::get('{id}', 'TicketApprovalController@index');
    Route::patch('{id}/approve', 'TicketApprovalController@approve')->name('.approve');
}); 

Route::prefix('conversation')->name('conversation')->group(function(){
    
    Route::post('list/{id}/{token}', 'ConversationController@conversations')->name('.list');
    Route::put('save/{id}', 'ConversationController@save')->name('.save');
    Route::delete('delete/{id}', 'ConversationController@delete')->name('.delete');

});

# Uploaded file controller
Route::get('get_file/{id}', 'FileUploadController@get_file')->name('get_uploaded_file');

Route::prefix('content')->name('content')->group(function(){

    Route::get('view/{control_number}/{token}', 'ContentController@view')->name('.view');
    Route::get('download/{control_number}', 'ContentController@download')->name('.download');

});




Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('user')->name('user')->group(function(){

    Route::get('', 'SenderController@index');
    Route::post('list', 'SenderController@list')->name('.list');
    Route::get('ticket-request', 'SenderController@show_request_form')->name('.creation-form');
    Route::put('ticket/save', 'SenderController@save')->name('.ticket.save');


    Route::prefix('application')->name('.application-form')->group(function(){
    
        Route::get('', 'SenderController@show_application')->name('.application-form');
        Route::put('save', 'SenderController@save_application')->name('.application-form.save');
        Route::get('view','SenderController@view_application_request')->name('.application-form.view');

    });



    Route::middleware('sender-ticket')->group(function(){

        Route::get('view/{id}', 'SenderController@view')->name('.view');
        Route::patch('close/{id}', 'SenderController@close')->name('.close');
        Route::patch('cancel/{id}', 'SenderController@cancel')->name('.cancel');
        Route::patch('open/{id}', 'SenderController@open')->name('.open');
        
    }); 

});

Route::prefix('inbox')->name('inbox')->group(function(){
    
    Route::get('', 'InboxController@index');
    Route::get('view/{id}', 'InboxController@view')->name('.view');
});




Route::prefix('dashboard')->name('dashboard')->group(function(){
    
    Route::get('', 'DashboardController@index');
    
    Route::post('staff_performance', 'DashboardController@staff_performance')->name('staff_performance');
    Route::post('rating_summary', 'DashboardController@rating_summary')->name('rating_summary');
        
});

Route::prefix('tickets')->name('tickets')->group(function(){
    
    Route::get('', 'Manage\TicketController@index');
    Route::post('list', 'Manage\TicketController@list')->name('.list');
    Route::get('view/{id}', 'Manage\TicketController@view')->name('.view');
    Route::post('notes/{id}', 'Manage\TicketController@notes')->name('.notes');
    
    Route::put('add_note/{id}', 'Manage\TicketActionController@add_note')->name('.add_note');
    Route::delete('remove_note/{id}', 'Manage\TicketActionController@remove_note')->name('.remove_note');
    Route::patch('update_note/{id}', 'Manage\TicketActionController@update_note')->name('.update_note');
    Route::patch('cater/{id}', 'Manage\TicketActionController@cater')->name('.cater');

    Route::middleware('ticket-action')->group(function(){
        
        Route::patch('solve/{id}', 'Manage\TicketActionController@solve')->name('.solve');
        Route::patch('hold/{id}', 'Manage\TicketActionController@hold')->name('.hold');
        Route::patch('un_hold/{id}', 'Manage\TicketActionController@un_hold')->name('.un_hold');
        Route::patch('cancel/{id}', 'Manage\TicketActionController@cancel')->name('.cancel');
        Route::patch('processing/{id}', 'Manage\TicketActionController@processing')->name('.processing');
        Route::patch('open/{id}', 'Manage\TicketActionController@open')->name('.open');
        Route::patch('escalate/{id}', 'Manage\TicketActionController@escalate')->name('.escalate');
        Route::put('add_reference/{id}', 'Manage\TicketActionController@add_reference')->name('.add_reference');
        Route::put('apply_approval/{id}', 'Manage\TicketActionController@apply_approval')->name('.apply_approval');
        Route::delete('cancel_approval/{id}', 'Manage\TicketActionController@cancel_approval')->name('.cancel_approval');
        Route::patch('custom_progress/{id}', 'Manage\TicketActionController@custom_progress')->name('.custom_progress');
        
    });

    
    Route::put('save', 'Manage\TicketActionController@save')->name('.save');

    
});
 

/**
 * ------------- ************** -------------
 * 
 * Route that needs administrator account
 * 
 * ------------- ************** -------------
 */

Route::middleware('maintain')->group(function(){

    Route::prefix('maintain')->name('maintain')->group(function(){

        Route::prefix('application')->name('.application')->group(function(){
            
            Route::get('', 'Manage\ApplicationSetupController@index');
            Route::post('list', 'Manage\ApplicationSetupController@list')->name('.list');
            Route::put('save', 'Manage\ApplicationSetupController@save')->name('.save');
            Route::post('info/{id}', 'Manage\ApplicationSetupController@info')->name('.info');
            Route::patch('update/{id}', 'Manage\ApplicationSetupController@update')->name('.update');
            Route::patch('update/format/{id}', 'Manage\ApplicationSetupController@format_update')->name('.update.format');
            Route::delete('delete/{id}', 'Manage\ApplicationSetupController@delete')->name('.delete');
            
        });

        Route::prefix('category')->name('.category')->group(function(){
            
            Route::get('', 'Manage\CategoryController@index');
            Route::post('list', 'Manage\CategoryController@list')->name('.list');
            Route::put('save', 'Manage\CategoryController@save')->name('.save');
            Route::post('info/{id}', 'Manage\CategoryController@info')->name('.info');
            Route::patch('update/{id}', 'Manage\CategoryController@update')->name('.update');
            Route::delete('delete/{id}', 'Manage\CategoryController@delete')->name('.delete');
            
        });

        Route::prefix('factory')->name('.factory')->group(function(){
            
            Route::get('', 'Manage\FactoryController@index');
            Route::post('list', 'Manage\FactoryController@list')->name('.list');
            Route::put('save', 'Manage\FactoryController@save')->name('.save');
            Route::post('info/{id}', 'Manage\FactoryController@info')->name('.info');
            Route::patch('update/{id}', 'Manage\FactoryController@update')->name('.update');
            Route::delete('delete/{id}', 'Manage\FactoryController@delete')->name('.delete');
            
        });

        Route::prefix('setting')->name('.setting')->group(function(){
            
            Route::get('', 'Manage\SettingController@index');
            Route::post('list', 'Manage\SettingController@list')->name('.list');
            Route::put('save', 'Manage\SettingController@save')->name('.save');
            Route::post('info/{id}', 'Manage\SettingController@info')->name('.info');
            Route::patch('update/{id}', 'Manage\SettingController@update')->name('.update');
            Route::delete('delete/{id}', 'Manage\SettingController@delete')->name('.delete');
            
        });
        
        Route::name('.canned')->group(function(){

            Route::prefix('canned-solution')->name('.solution')->group(function(){
                
                Route::get('', 'Manage\Canned\CannedSolutionController@index');
                Route::post('list', 'Manage\Canned\CannedSolutionController@list')->name('.list');
                Route::put('save', 'Manage\Canned\CannedSolutionController@save')->name('.save');
                Route::post('info/{id}', 'Manage\Canned\CannedSolutionController@info')->name('.info');
                Route::patch('update/{id}', 'Manage\Canned\CannedSolutionController@update')->name('.update');
                Route::delete('delete/{id}', 'Manage\Canned\CannedSolutionController@delete')->name('.delete');
                
            });

        });



    });

    Route::prefix('accounts')->name('accounts')->group(function(){

        Route::get('','AccountController@index');
        Route::post('list','AccountController@list')->name('.list');
        Route::post('fetch/{id}','AccountController@fetch')->name('.fetch');
        Route::put('save','AccountController@save')->name('.save');
        Route::patch('update/{id}','AccountController@update')->name('.update');
        Route::delete('delete/{id}','AccountController@delete')->name('.delete');

    });

});

/**
 * ------------- END -------------
 */

 Route::prefix('report')->name('report')->group(function(){
    
    Route::prefix('tickets')->name('.tickets')->group(function(){
    
        Route::get('','TicketReportController@index');
        Route::post('list','ReportController@list')->name('.list'); 
    
    });

    Route::prefix('tasks')->name('.tasks')->group(function(){

        Route::get('','TaskReportController@index');
        Route::post('list','TaskReportController@list')->name('.list');
        Route::get('download','TaskReportController@download')->name('.download'); 
    
    });

    Route::prefix('projects')->name('.projects')->group(function(){
        Route::get('','ProjectReportController@index');
        Route::post('list','ProjectReportController@list')->name('.list'); 
    });

});


Route::prefix('projects')->name('projects')->group(function(){
    
    Route::get('', 'ProjectController@index');
    Route::post('list', 'ProjectController@list')->name('.list');
    Route::get('download', 'ProjectController@download')->name('.download');
    Route::put('save', 'ProjectController@save')->name('.save');
    Route::get('view/{id}', 'ProjectController@view')->name('.view');
    Route::post('info/{id}', 'ProjectController@info')->name('.info');
    Route::patch('update/{id}', 'ProjectController@update')->name('.update');
    Route::delete('delete/{id}', 'ProjectController@delete')->name('.delete');
    Route::put('save-task/{id}', 'ProjectController@save_task')->name('.save_task');
});

Route::prefix('tasks')->name('tasks')->group(function(){
    
    Route::get('', 'TaskController@index');
    Route::put('save', 'TaskController@save')->name('.save');
    Route::post('list', 'TaskController@list')->name('.list');
    Route::get('download', 'TaskController@download')->name('.download');

    Route::post('kanban', 'TaskController@kanban')->name('.kanban');
    Route::patch('update-state/{id}', 'TaskController@update_state')->name('.update_state');
    Route::delete('delete/{id}', 'TaskController@delete')->name('.delete');
    Route::patch('cancel/{id}', 'TaskController@cancel')->name('.cancel');
    Route::post('info/{id}', 'TaskController@info')->name('.info');
    Route::patch('update/{id}', 'TaskController@update')->name('.update');
    Route::post('progress', 'TaskController@progress')->name('.progress');

});
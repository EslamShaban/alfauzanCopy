<?php

    /*---------------------------------Start Of FrontEnd--------------------------*/

    use Illuminate\Support\Facades\Auth;

    Route ::get( '/', function () {

        if ( auth ::check() )
            return redirect( route( 'dashboard' ) );
        return view( 'site.index' );
    } );

    #project cats
    Route ::post( 'project-cats', [
        'uses'  => 'dashboard\CategoryController@projectCats',
        'as'    => 'project_cats',
        'title' => 'project cats'
    ] );


    /*---------------------------------End Of FrontEnd--------------------------*/


    /*---------------------------------Start Of DashBoard--------------------------*/

    Route ::group( [ 'prefix' => 'admin', 'middleware' => [ 'auth', 'isActive', 'Manager', 'checkRole'/*, 'smtpAndFcmConfig'*/ ] ],
        function () {
            /*Start Of DashBoard Controller (Intro Page)*/
            Route ::get( 'dashboard', [
                'uses'  => 'DashBoardController@Index',
                'as'    => 'dashboard',
                'icon'  => '<i class="icon-home4"></i>',
                'title' => 'الرئيسيه'
            ] );


            /*------------ Start Of ContactUsController ----------*/

            #messages page
            Route ::get( 'inbox-page', [
                'uses'  => 'ContactUsController@InboxPage',
                'as'    => 'inbox',
                'title' => 'الرسائل',
                'icon'  => '<i class="icon-inbox-alt"></i>',
                'child' => [ 'showmessage', 'deletemessage', 'sendsms', 'sendemail' ]
            ] );

            #show message page
            Route ::get( 'show-message/{id}', [
                'uses'  => 'ContactUsController@ShowMessage',
                'as'    => 'showmessage',
                'title' => 'عرض الرساله'
            ] );

            #send sms
            Route ::post( 'send-sms', [
                'uses'  => 'ContactUsController@SMS',
                'as'    => 'sendsms',
                'title' => 'ارسال SMS'
            ] );

            #send email
            Route ::post( 'send-email', [
                'uses'  => 'ContactUsController@EMAIL',
                'as'    => 'sendemail',
                'title' => 'ارسال Email'
            ] );

            #delete message
            Route ::post( 'delete-message', [
                'uses'  => 'ContactUsController@DeleteMessage',
                'as'    => 'deletemessage',
                'title' => 'حذف الرساله'
            ] );

            /*------------ End Of ContactUsController ----------*/

            /*------------ start Of UsersController ----------*/

            #users list
            Route ::get( 'users', [
                'uses'  => 'UsersController@Users',
                'as'    => 'users',
                'title' => 'الاعضاء',
                'icon'  => '<i class="icon-vcard"></i>',
                'child' => [
                    'adduser',
                    'updateuser',
                    'deleteuser',
                    'emailallusers',
                    'smsallusers',
                    'notificationallusers',
                    'sendcurrentemail',
                    'sendcurrentsms',
                    'sendcurrentnotification'
                ]
            ] );

            #add user
            Route ::post( 'add-user', [
                'uses'  => 'UsersController@AddUser',
                'as'    => 'adduser',
                'title' => 'اضافة عضو'
            ] );

            #update user
            Route ::post( 'update-user', [
                'uses'  => 'UsersController@UpdateUser',
                'as'    => 'updateuser',
                'title' => 'تحديث عضو'
            ] );

            #delete user
            Route ::post( 'delete-user', [
                'uses'  => 'UsersController@deleteUser',
                'as'    => 'deleteuser',
                'title' => 'حذف عضو'
            ] );

            #email for all users
            Route ::post( 'email-users', [
                'uses'  => 'UsersController@EmailMessageAll',
                'as'    => 'emailallusers',
                'title' => 'ارسال email للجميع'
            ] );

            #sms for all users
            Route ::post( 'sms-users', [
                'uses'  => 'UsersController@SmsMessageAll',
                'as'    => 'smsallusers',
                'title' => 'ارسال sms للجميع'
            ] );


            #send email for current user
            Route ::post( 'send-current-email', [
                'uses'  => 'UsersController@SendEmail',
                'as'    => 'sendcurrentemail',
                'title' => 'ارساله email لعضو'
            ] );

            #send sms for current user
            Route ::post( 'send-current-sms', [
                'uses'  => 'UsersController@SendSMS',
                'as'    => 'sendcurrentsms',
                'title' => 'ارساله sms لعضو'
            ] );

            /*------------ End Of UsersController ----------*/

            /*------------ Start Of ReportsController ----------*/

            #reports page
            Route ::get( 'reports-page', [
                'uses'  => 'ReportsController@ReportsPage',
                'as'    => 'reportspage',
                'title' => 'التقارير',
                'icon'  => '<i class=" icon-flag7"></i>',
                'child' => [/*'deleteusersreports',*/ 'deletesupervisorsreports' ]
            ] );

            //			#delete users reports
            //			Route::post( 'delete-users-reporst', [
            //				'uses'  => 'ReportsController@DeleteUsersReports',
            //				'as'    => 'deleteusersreports',
            //				'title' => 'حذف تقارير الاعضاء'
            //			] );

            #delete supervisors reports
            Route ::post( 'delete-supervisors-reporst', [
                'uses'  => 'ReportsController@DeleteSupervisorsReports',
                'as'    => 'deletesupervisorsreports',
                'title' => 'حذف تقارير المشرفين'
            ] );
            /*------------ End Of ReportsController ----------*/

            /*------------ start Of PermissionsController ----------*/
            #permissions list
            Route ::get( 'permissions-list', [
                'uses'  => 'PermissionsController@PermissionsList',
                'as'    => 'permissionslist',
                'title' => 'قائمة الصلاحيات',
                'icon'  => '<i class="icon-safe"></i>',
                'child' => [
                    'addpermissionspage',
                    'addpermission',
                    'editpermissionpage',
                    'updatepermission',
                    'deletepermission'
                ]
            ] );

            #add permissions page
            Route ::get( 'permissions', [
                'uses'  => 'PermissionsController@AddPermissionsPage',
                'as'    => 'addpermissionspage',
                'title' => 'اضافة صلاحيه',

            ] );

            #add permission
            Route ::post( 'add-permission', [
                'uses'  => 'PermissionsController@AddPermissions',
                'as'    => 'addpermission',
                'title' => 'تمكين اضافة صلاحيه'
            ] );

            #edit permissions page
            Route ::get( 'edit-permissions/{id}', [
                'uses'  => 'PermissionsController@EditPermissions',
                'as'    => 'editpermissionpage',
                'title' => 'تعديل صلاحيه'
            ] );

            #update permission
            Route ::post( 'update-permission', [
                'uses'  => 'PermissionsController@UpdatePermission',
                'as'    => 'updatepermission',
                'title' => 'تمكين تعديل صلاحيه'
            ] );

            #delete permission
            Route ::post( 'delete-permission', [
                'uses'  => 'PermissionsController@DeletePermission',
                'as'    => 'deletepermission',
                'title' => 'حذف صلاحيه'
            ] );

            /*------------ End Of PermissionsController ----------*/

            /*------------ Start Of MoneyAccountsController ----------
			Route::get('money-accounts',[
				'uses' =>'MoneyAccountsController@MoneyAccountsPage',
				'as'   =>'moneyaccountspage',
				'icon' =>'<i class="icon-cash3"></i>',
				'title'=>'الحسابات الماليه',
				'child'=>['moneyaccept','moneyacceptdelete','moneydelete']
			]);

			#accept
			Route::post('accept',[
				'uses' =>'MoneyAccountsController@Accept',
				'as'   =>'moneyaccept',
				'title'=>'تأكيد معامله بنكيه',
			]);

			#accept and delete
			Route::post('accept-delete',[
				'uses' =>'MoneyAccountsController@AcceptAndDelete',
				'as'   =>'moneyacceptdelete',
				'title'=>'تأكيد مع حذف',
			]);

			#delete
			Route::post('money-delete',[
				'uses' =>'MoneyAccountsController@Delete',
				'as'   =>'moneydelete',
				'title'=>'حذف معامله بنكيه',
			]);
			------------ End Of MoneyAccountsController ----------*/

            /*------------ Start Of SettingController ----------*/

            #setting page
            Route ::get( 'setting', [
                'uses'  => 'SettingController@Setting',
                'as'    => 'setting',
                'title' => 'الاعدادات',
                'icon'  => '<i class="icon-wrench"></i>',
                'child' => [
                    'addsocials',
                    'updatesocials',
                    'deletesocial',
                    //					'updatesmtp',
                    //					'updatesms',
                    //					'updateonesignal',
                    //					'updatefcm',
                    'updatesitesetting',
                    'updateseo',
                    'updatesitecopyright',
                    //					'updateemailtemplate',
                    //					'updategoogleanalytics',
                    //					'updatelivechat'
                ]
            ] );

            #add socials media
            Route ::post( 'add-socials', [
                'uses'  => 'SettingController@AddSocial',
                'as'    => 'addsocials',
                'title' => 'اضافة مواقع التواصل'
            ] );

            #update socials media
            Route ::post( 'update-socials', [
                'uses'  => 'SettingController@UpdateSocial',
                'as'    => 'updatesocials',
                'title' => 'تحديث مواقع التواصل'
            ] );

            #delete social
            Route ::post( 'delete-social', [
                'uses'  => 'SettingController@DeleteSocial',
                'as'    => 'deletesocial',
                'title' => 'حذف مواقع التاوصل'
            ] );

            #update SMTP
            Route ::post( 'update-smtp', [
                'uses'  => 'SettingController@SMTP',
                'as'    => 'updatesmtp',
                'title' => 'تحديث SMTP'
            ] );

            #update SMS
            Route ::post( 'update-sms', [
                'uses'  => 'SettingController@SMS',
                'as'    => 'updatesms',
                'title' => 'تحديث SMS'
            ] );

            #update OneSignal
            Route ::post( 'update-onesignal', [
                'uses'  => 'SettingController@OneSignal',
                'as'    => 'updateonesignal',
                'title' => 'تحديث OneSignal'
            ] );

            #update FCM
            Route ::post( 'update-FCM', [
                'uses'  => 'SettingController@FCM',
                'as'    => 'updatefcm',
                'title' => 'تحديث FCM'
            ] );

            #update SiteSetting
            Route ::post( 'update-sitesetting', [
                'uses'  => 'SettingController@SiteSetting',
                'as'    => 'updatesitesetting',
                'title' => 'تحديث الاعدادات العامه'
            ] );

            #update SEO
            Route ::post( 'update-seo', [
                'uses'  => 'SettingController@SEO',
                'as'    => 'updateseo',
                'title' => 'تحديث SEO'
            ] );

            #update footerCopyRight
            Route ::post( 'update-sitecopyright', [
                'uses'  => 'SettingController@SiteCopyRight',
                'as'    => 'updatesitecopyright',
                'title' => 'تحديث حقوق الموقع'
            ] );

            #update email template
            Route ::post( 'update-emailtemplate', [
                'uses'  => 'SettingController@EmailTemplate',
                'as'    => 'updateemailtemplate',
                'title' => 'تحديث قالب الايميل'
            ] );

            #update api google analytics
            Route ::post( 'update-google-analytics', [
                'uses'  => 'SettingController@GoogleAnalytics',
                'as'    => 'updategoogleanalytics',
                'title' => 'تحديث google analytics'
            ] );

            #update api live chat
            Route ::post( 'update-live-chat', [
                'uses'  => 'SettingController@LiveChat',
                'as'    => 'updatelivechat',
                'title' => 'تحديث live chat'
            ] );

            /*------------ End Of SettingController ----------*/

            /*------------ start Of Category ----------*/

            #users list
            Route ::get( 'categories', [
                'uses'  => 'dashboard\CategoryController@categories',
                'as'    => 'categories',
                'title' => 'الاقسام',
                'icon'  => '<i class="fa fa-list"></i>',
                'child' => [
                    'add_category',
                    'update_category',
                    'delete_category',
                ]
            ] );

            #add category
            Route ::post( 'add-category', [
                'uses'  => 'dashboard\CategoryController@addCategory',
                'as'    => 'add_category',
                'title' => 'اضافة قسم'
            ] );

            #update category
            Route ::post( 'update-category', [
                'uses'  => 'dashboard\CategoryController@updateCategory',
                'as'    => 'update_category',
                'title' => 'تحديث قسم'
            ] );

            #delete category
            Route ::post( 'delete-category', [
                'uses'  => 'dashboard\CategoryController@deleteCategory',
                'as'    => 'delete_category',
                'title' => 'حذف قسم'
            ] );

            /*------------ End Of CategoryController ----------*/

            /*------------ start Of project ----------*/

            #projects list
            Route ::get( 'projects', [
                'uses'  => 'dashboard\CategoryController@projects',
                'as'    => 'projects',
                'title' => 'المخططات',
                'icon'  => '<i class="fa fa-building"></i>',
                'child' => [
                    'add_project',
                    'update_project',
                    'delete_project',
                    'p_categories',
                    'add_p_category',
                    'update_p_category',
                    'delete_p_category',
                ]
            ] );

            #add project
            Route ::post( 'add-project', [
                'uses'  => 'dashboard\CategoryController@addProject',
                'as'    => 'add_project',
                'title' => 'اضافة مخطط'
            ] );

            #update project
            Route ::post( 'update-project', [
                'uses'  => 'dashboard\CategoryController@updateProject',
                'as'    => 'update_project',
                'title' => 'تحديث مخطط'
            ] );

            #delete project
            Route ::post( 'delete-project', [
                'uses'  => 'dashboard\CategoryController@deleteProject',
                'as'    => 'delete_project',
                'title' => 'حذف مخطط'
            ] );

            #all project category
            Route ::get( 'project-categories', [
                'uses'  => 'dashboard\CategoryController@projectCategories',
                'as'    => 'p_categories',
                'title' => 'اقسام المخطط'
            ] );

            #add category
            Route ::post( 'add-project-category', [
                'uses'  => 'dashboard\CategoryController@addProjectCategory',
                'as'    => 'add_p_category',
                'title' => 'اضافة قسم للمخطط'
            ] );

            #update category
            Route ::post( 'update-project-category', [
                'uses'  => 'dashboard\CategoryController@updateProjectCategory',
                'as'    => 'update_p_category',
                'title' => 'تحديث قسم فى المخطط'
            ] );

            #delete category
            Route ::post( 'delete-project-category', [
                'uses'  => 'dashboard\CategoryController@deleteProjectCategory',
                'as'    => 'delete_p_category',
                'title' => 'حذف قسم من المخطط'
            ] );


            /*------------ End Of Project Controller ----------*/

            /*------------ start Of Ads ----------*/

            #users list
            Route ::get( 'ads', [
                'uses'  => 'dashboard\AdsController@ads',
                'as'    => 'ads',
                'title' => 'الاعلانات',
                'icon'  => '<i class="fa fa-line-chart"></i>',
                'child' => [
                    'offer_ads',
                    'add_ad',
                    'update_ad',
                    'delete_ad',
                    'change_ad_status',
                    'ad_images',
                    'add_image',
                    'delete_image',
                    'ad_rooms',
                    'ad_chats',
                    'send_msg',
                ]
            ] );

            #offered ads
            Route ::get( 'offer-ads', [
                'uses'  => 'dashboard\AdsController@offerAds',
                'as'    => 'offer_ads',
                'title' => 'العروض'
            ] );

            #add ad
            Route ::post( 'add-ad', [
                'uses'  => 'dashboard\AdsController@addAd',
                'as'    => 'add_ad',
                'title' => 'اضافة اعلان'
            ] );

            #update ad
            Route ::post( 'update-ad', [
                'uses'  => 'dashboard\AdsController@updateAd',
                'as'    => 'update_ad',
                'title' => 'تحديث اعلان'
            ] );

            #delete ad
            Route ::post( 'delete-ad', [
                'uses'  => 'dashboard\AdsController@deleteAd',
                'as'    => 'delete_ad',
                'title' => 'حذف اعلان'
            ] );

            #change ad status
            Route ::any( 'change_ad_status', [
                'uses'  => 'dashboard\AdsController@changeStatus',
                'as'    => 'change_ad_status',
                'title' => 'تغيير حالة الاعلان'
            ] );


            #ad images
            Route ::get( 'ad-images/{id}', [
                'uses'  => 'dashboard\AdsController@images',
                'as'    => 'ad_images',
                'title' => 'صور الاعلان'
            ] );

            #add image
            Route ::post( 'add-image', [
                'uses'  => 'dashboard\AdsController@addImage',
                'as'    => 'add_image',
                'title' => 'اضافة صورة'
            ] );

            #delete image
            Route ::get( 'delete-img', [
                'uses'  => 'dashboard\AdsController@deleteImage',
                'as'    => 'delete_image',
                'title' => 'حذف صورة'
            ] );

            #ad rooms
            Route ::get( 'ad-rooms/{id}', [
                'uses'  => 'dashboard\AdsController@rooms',
                'as'    => 'ad_rooms',
                'title' => 'كل الغرف'
            ] );

            # get chat messages
            Route ::get( 'chat/{room_id}', [
                'uses'  => 'dashboard\AdsController@chat',
                'as'    => 'ad_chats',
                'title' => 'الرسائل'
            ] );

            # send message
            Route ::post( 'send-msg', [
                'uses'  => 'dashboard\AdsController@send',
                'as'    => 'send_msg',
                'title' => 'ارسال رسالة'
            ] );


            /*------------ End Of Ads Controller ----------*/

            /*------------ start Of repairs ----------*/

            #all repairs orders
            Route ::get( 'repairs', [
                'uses'  => 'dashboard\RepairController@repairs',
                'as'    => 'repairs',
                'title' => 'الصيانة',
                'icon'  => '<i class="fa fa-pencil"></i>',
                'child' => [

                    'delete_repair_order',
                    'repair_types',
                    'add_repair_type',
                    'update_repair_type',
                    'delete_repair_type',
                ]
            ] );

            #delete repair order
            Route ::post( 'delete-repair-order', [
                'uses'  => 'dashboard\RepairController@deleteRepairOrder',
                'as'    => 'delete_repair_order',
                'title' => 'حذف طلب الصيانة'
            ] );

            # repair types
            Route ::get( 'repair-types', [
                'uses'  => 'dashboard\RepairController@repairTypes',
                'as'    => 'repair_types',
                'title' => 'انواع الصيانة'
            ] );

            #add repair type
            Route ::post( 'add-repair-type', [
                'uses'  => 'dashboard\RepairController@addRepairType',
                'as'    => 'add_repair_type',
                'title' => 'اضافة نوع صيانة'
            ] );

            #update repair type
            Route ::post( 'update-repair-type', [
                'uses'  => 'dashboard\RepairController@updateRepairType',
                'as'    => 'update_repair_type',
                'title' => 'تحديث نوع الصيانة'
            ] );

            #delete repair type
            Route ::post( 'delete-repair-type', [
                'uses'  => 'dashboard\RepairController@deleteRepairType',
                'as'    => 'delete_repair_type',
                'title' => 'حذف نوع الصيانة'
            ] );


            /*------------ End Of repair Controller ----------*/

            /*------------ start Of jobs ----------*/

            #all job applicants
            Route ::get( 'jobs', [
                'uses'  => 'dashboard\HomeController@jobs',
                'as'    => 'jobs',
                'title' => 'طلبات العمل',
                'icon'  => '<i class="fa fa-suitcase"></i>',
                'child' => [

                    'delete_job',
                ]
            ] );

            #delete job
            Route ::post( 'delete-job', [
                'uses'  => 'dashboard\HomeController@deleteJob',
                'as'    => 'delete_job',
                'title' => 'حذف طلب العمل'
            ] );


            /*------------ End Of jobs Controller ----------*/

            /*------------ start Of Orders ----------*/

            #users list
            Route ::get( 'orders', [
                'uses'  => 'dashboard\OrderController@orders',
                'as'    => 'orders',
                'title' => 'طلبات الحجز',
                'icon'  => '<i class="fa fa-clone"></i>',
                'child' => [

                    'accept_order',
                    'refuse_order',
                    'reasons',
                    'delete_order',
                    'add_reason',
                    'update_reason',
                    'delete_reason',
                ]
            ] );

            #accept order
            Route ::post( 'accept-order', [
                'uses'  => 'dashboard\OrderController@acceptOrder',
                'as'    => 'accept_order',
                'title' => 'قبول الطلب'
            ] );

            #refuse order
            Route ::post( 'refuse-order', [
                'uses'  => 'dashboard\OrderController@refuseOrder',
                'as'    => 'refuse_order',
                'title' => 'رفض الطلب'
            ] );

            #delete order
            Route ::post( 'delete-order', [
                'uses'  => 'dashboard\OrderController@deleteOrder',
                'as'    => 'delete_order',
                'title' => 'حذف الطلب'
            ] );

            #order reasons
            Route ::get( 'reasons', [
                'uses'  => 'dashboard\OrderController@reasons',
                'as'    => 'reasons',
                'title' => 'اهداف الحجز'
            ] );

            #add reason
            Route ::post( 'add-reason', [
                'uses'  => 'dashboard\OrderController@addReason',
                'as'    => 'add_reason',
                'title' => 'اضافة هدف الحجز'
            ] );

            #update reason
            Route ::post( 'update-reason', [
                'uses'  => 'dashboard\OrderController@updateReason',
                'as'    => 'update_reason',
                'title' => 'تحديث هدف الحجز'
            ] );

            #delete reason
            Route ::post( 'delete-reason', [
                'uses'  => 'dashboard\OrderController@deleteReason',
                'as'    => 'delete_reason',
                'title' => 'حذف هدف الحجز'
            ] );
            /*------------ End Of Ads Controller ----------*/

	        /*------------ start Of auction ----------*/

	        #all auctions
	        Route ::get( 'auctions', [
		        'uses'  => 'dashboard\HomeController@auctions',
		        'as'    => 'auctions',
		        'title' => 'طلبات العملاء',
		        'icon'  => '<i class="fa fa-suitcase"></i>',
		        'child' => [

			        'delete_auction',
		        ]
	        ] );

	        #delete job
	        Route ::post( 'delete_auction', [
		        'uses'  => 'dashboard\HomeController@deleteAuction',
		        'as'    => 'delete_auction',
		        'title' => 'حذف طلب العميل'
	        ] );


	        /*------------ End Of jobs Controller ----------*/

            /*------------ start Of Branch ----------*/

            #users list
            Route ::get( 'branches', [
                'uses'  => 'dashboard\HomeController@branches',
                'as'    => 'branches',
                'title' => 'فروعنا',
                'icon'  => '<i class="fa fa-list"></i>',
                'child' => [
                    'add_branch',
                    'update_branch',
                    'delete_branch',
                ]
            ] );

            #add category
            Route ::post( 'add-branch', [
                'uses'  => 'dashboard\HomeController@addBranch',
                'as'    => 'add_branch',
                'title' => 'اضافة فرع'
            ] );

            #update category
            Route ::post( 'update-branch', [
                'uses'  => 'dashboard\HomeController@updateBranch',
                'as'    => 'update_branch',
                'title' => 'تحديث فرع'
            ] );

            #delete category
            Route ::post( 'delete-branch', [
                'uses'  => 'dashboard\HomeController@deleteBranch',
                'as'    => 'delete_branch',
                'title' => 'حذف فرع'
            ] );

            /*------------ End Of CategoryController ----------*/
            /*------------ start Of banner ----------*/

            #users list
            Route ::get( 'banners', [
                'uses'  => 'dashboard\HomeController@banners',
                'as'    => 'banners',
                'title' => 'البنرات الاعلانية',
                'icon'  => '<i class="fa fa-list"></i>',
                'child' => [
                    'add_banner',
                    'update_banner',
                    'delete_banner',
                ]
            ] );

            #add banner
            Route ::post( 'add_banner', [
                'uses'  => 'dashboard\HomeController@addBanner',
                'as'    => 'add_banner',
                'title' => 'اضافة بنر اعلاني'
            ] );

            #update banner
            Route ::post( 'update-banner', [
                'uses'  => 'dashboard\HomeController@updateBanner',
                'as'    => 'update_banner',
                'title' => 'تحديث بنر اعلاني'
            ] );

            #delete banner
            Route ::post( 'delete-banner', [
                'uses'  => 'dashboard\HomeController@deleteBanner',
                'as'    => 'delete_banner',
                'title' => 'حذف بنر اعلاني'
            ] );

            /*------------ End Of CategoryController ----------*/


        } );
    /*-------------------------------End Of DashBoard--------------------------------*/


    //Login Route
    Route ::get( '/login/', 'Auth\LoginController@showLoginForm' ) -> name( 'login' );
    Route ::post( '/login/', 'Auth\LoginController@login' );
    Route ::get( '/logout', 'Auth\LoginController@logout' ) -> name( 'logout' );

    // Route::get('register', 'Auth\RegisterController@showRegistrationForm');
    // Route::post('register','RegisterUserController@Register');
    //Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes
    Route ::get( 'password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm' ) -> name( 'password.request' );
    Route ::post( 'password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail' );
    Route ::get( 'password/reset/{token}', 'Auth\ResetPasswordController@showResetForm' ) -> name( 'password.reset' );
    Route ::post( 'password/reset', 'Auth\ResetPasswordController@reset' );

    //ad info
    Route ::get( 'ad-info/{id}', 'dashboard\HomeController@adInfo' );
    Route ::get( 'auction', 'dashboard\HomeController@showAuction' );
    Route ::post( 'auction', 'dashboard\HomeController@auction' )->name('auction');

module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {// Task
            dist: {// Target
                options: {// Target options
                    //style: 'compressed'
                    compress: false,
                    sourcemap: 'none'
                },
                files: {// Dictionary of files
                    'frontend/web/static/dist/css/project-vendors.css': 'frontend/web/static/dist/css/sass/project-vendors.scss',
                    'frontend/web/static/dist/css/project.css': 'frontend/web/static/dist/css/sass/project.scss',
                    'frontend/web/static/dist/css/reviewdata.css': 'frontend/web/static/dist/css/sass/reviewdata.scss',
                    'frontend/web/static/admin/css/default.css': 'frontend/web/static/admin/css/sass/default.scss',
                    'backend/web/static/css/default.css': 'backend/web/static/css/sass/default.scss',
                    'backend/web/static/css/vendors.css': 'backend/web/static/css/sass/vendors.scss'

                }
            }
        },
        watch: {
            css: {
                files: [
                    'frontend/web/static/dist/css/sass/**/*.scss',
                    'frontend/web/static/admin/css/sass/**/*.scss',
                    'backend/web/static/css/sass/**/*.scss'
                ],
                tasks: ['sass'],
                options: {
                    spawn: false,
                    livereload: true
                }
            }
        },
        uglify: {
            options: {
                mangle: false
            },
            admin: {
                files: {
                    'backend/web/static/dist/deploy/app.min.js': [
                        "backend/web/static/dist/js/vendors/jquery/jquery-3.5.1.min.js",
                        "backend/web/static/dist/js/vendors/bootstrap-4.5.3/js/popper.min.js",
                        "backend/web/static/dist/js/vendors/bootstrap-4.5.3/js/bootstrap.min.js",
                        "backend/web/static/dist/js/vendors/bootstrap-datetimepicker/moment.min.js",
                        "backend/web/static/dist/js/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js",
                        "backend/web/static/dist/js/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js",
                        "backend/web/static/dist/js/vendors/bootbox/bootbox.all.min.js",
                        "backend/web/static/dist/js/vendors/chosen-select/chosen.jquery.js",
                        "backend/web/static/dist/js/vendors/sumo-select/jquery.sumoselect.min.js",
                        "backend/web/static/dist/js/vendors/switchery/switchery.js",
                        "backend/web/static/dist/js/vendors/tabs-scroll/tabs-scroll.js",
                        "backend/web/static/dist/js/vendors/owl-carousel/owl.carousel.min.js",
                        "backend/web/static/dist/js/vendors/bootstrap-notify-master/bootstrap-notify.min.js",
                        "backend/web/static/dist/js/vendors/crypto-js/crypto-js.js",
                        "backend/web/static/dist/js/vendors/fancybox-2.1.7/source/jquery.fancybox.js",
                        "backend/web/static/dist/js/vendors/highchart/highcharts.js",
                        "backend/web/static/dist/js/theme.js",
                        "backend/web/static/dist/dev/common/yii.js",
                        "backend/web/static/dist/dev/common/yii.activeForm.js",
                        "backend/web/static/dist/dev/common/yii.gridView.js",
                        "backend/web/static/dist/dev/common/yii.validation.js",
                        "backend/web/static/dist/dev/common/jquery.pjax.js",
                        "backend/web/static/dist/dev/common/yii.captcha.js",
                        "backend/web/static/dist/dev/common/common.js",
                        "backend/web/static/dist/dev/common/location.js",
                        "backend/web/static/dist/dev/common/auth.js",
                        "backend/web/static/dist/dev/common/captcha.js",
                        "backend/web/static/dist/dev/common/form-sanitization.js",
                        "backend/web/static/dist/dev/common/general.js",
                        "backend/web/static/dist/dev/qualification/qualification.js",
                        "backend/web/static/dist/dev/applicant/applicant.js",
                        "backend/web/static/dist/dev/report/report.js",
                        "backend/web/static/dist/dev/user/user.js",
                        "backend/web/static/dist/dev/log-profile/log-profile.js",
                        "backend/web/static/dist/dev/dashboard/dashboard.js",
                        "backend/web/static/dist/dev/profile/profile.js",
                        "backend/web/static/dist/dev/export/export.js"
                    ]

                }
            },
            frontend: {
                files: {
                    'frontend/web/static/dist/deploy/app.min.js': [
                        "frontend/web/static/dist/vendors/jquery/jquery-3.5.1.min.js",
                        "frontend/web/static/dist/vendors/bootstrap-4.5.3/js/popper.min.js",
                        "frontend/web/static/dist/vendors/bootstrap-4.5.3/js/bootstrap.min.js",
                        "frontend/web/static/dist/vendors/bootstrap-datetimepicker/moment.min.js",
                        "frontend/web/static/dist/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js",
                        "frontend/web/static/dist/vendors/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
                        "frontend/web/static/dist/vendors/chosen-select/chosen.jquery.js",
                        "frontend/web/static/dist/vendors/sumo-select/jquery.sumoselect.min.js",
                        "frontend/web/static/dist/vendors/switchery/switchery.js",
                        "frontend/web/static/dist/vendors/bootstrap-notify-master/bootstrap-notify.min.js",
                        "frontend/web/static/dist/vendors/dropzone/dropzone.js",
                        "frontend/web/static/dist/vendors/bootbox/bootbox.min.js",
                        "frontend/web/static/dist/vendors/handlebars/js/handlebars.js",
                        "frontend/web/static/dist/vendors/cropper/jquery-cropper.js",
                        "frontend/web/static/dist/vendors/crypto-js/crypto-js.js",
                        "frontend/web/static/dist/dev/common/yii.js",
                        "frontend/web/static/dist/dev/common/yii.activeForm.js",
                        "frontend/web/static/dist/dev/common/yii.gridView.js",
                        "frontend/web/static/dist/dev/common/yii.validation.js",
                        "frontend/web/static/dist/dev/common/jquery.pjax.js",
                        "frontend/web/static/dist/dev/common/yii.captcha.js",
                        "frontend/web/static/dist/dev/common/uploadfile.js",
                        "frontend/web/static/dist/dev/common/auth.js",
                        "frontend/web/static/dist/dev/common/captcha.js",
                        "frontend/web/static/dist/dev/common/common.js",
                        "frontend/web/static/dist/dev/common/form-sanitization.js",
                        "frontend/web/static/dist/dev/common/function.js",
                        "frontend/web/static/dist/dev/common/validation.js",
                        "frontend/web/static/dist/dev/common/location.js",
                        "frontend/web/static/dist/dev/common/crop.js",
                        "frontend/web/static/dist/dev/common/general.js",
                        "frontend/web/static/dist/dev/registration/registrationv2.js",
                        "frontend/web/static/dist/dev/applicant-post/applicant-post.js",
                        "frontend/web/static/dist/dev/log-applicant/log-applicant.js",
                        "frontend/web/static/dist/dev/log-applicant/log-profile.js",
                        "frontend/web/static/dist/dev/classified-criteria/classified-criteria.js"
                    ],
                }
            }
        },
        //Minify Css Files
        cssmin: {
            options: {
                // processImport: false,
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            admin: {
                files: {
                    'backend/web/static/dist/deploy/app.min.css': [
                        "backend/web/static/css/vendors.css",
                        "backend/web/static/css/default.css",
                        "backend/web/static/dist/js/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css",
                        "backend/web/static/dist/js/vendors/fancybox-2.1.7/source/jquery.fancybox.css"
                    ]
                }
            },
            frontend: {
                files: {
                    'frontend/web/static/dist/deploy/app.min.css': [
                        "frontend/web/static/dist/css/project-vendors.css",
                        "frontend/web/static/dist/css/project.css",
                        "frontend/web/static/dist/vendors/dropzone/css/dropzone.css",
                        "frontend/web/static/dist/vendors/cropper/css/cropper.css",
                        "frontend/web/static/dist/vendors/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
                        "frontend/web/static/admin/css/default.css",
                        "frontend/web/static/admin/css/reset.css"
                    ]
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');


    // Default task(s).
    grunt.registerTask('default', ['uglify', 'cssmin']);



};
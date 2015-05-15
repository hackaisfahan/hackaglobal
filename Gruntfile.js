module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            all: ["public/backend/assets/build/*"]
        },
        bower_concat: {
            all: {
                dest: 'public/backend/assets/js/lib.js',
                cssDest: 'public/backend/assets/css/lib.css',
                exclude: [
                    'bootstrap',
                    "iranian-sans"
                ],
                dependencies: {
                    'bootswatch-dist': 'jquery',
                    'yamm3' : 'bootswatch-dist',
                    'bootstrap-filestyle' : 'bootswatch-dist',
                    'bootstrap-rtl' : 'bootswatch-dist',
                    'bootbox' : 'jquery'
                },
                bowerOptions: {
                    relative: false
                }
            }
        },
        uglify: {
            lib: {
                src: '<%= bower_concat.all.dest %>',
                dest: 'public/backend/assets/js/lib.min.js'
            },
            app: {
                src: 'public/backend/assets/js/app.js',
                dest: 'public/backend/assets/js/app.min.js'
            }
        },
        cssmin: {
            lib: {
                src: '<%= bower_concat.all.cssDest %>',
                dest: 'public/backend/assets/css/lib.min.css'
            },
            app: {
                src: 'public/backend/assets/css/app.css',
                dest: 'public/backend/assets/css/app.min.css'
            }
        },
        copy: {
            main: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['bower_components/*/fonts/**.*'],
                        dest: 'public/backend/assets/fonts/'
                    }
                ]
            }
        }
    });

    // Load required tasks
    grunt.loadNpmTasks('grunt-bower-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');

    // Default task(s).
    grunt.registerTask('default', ['clean', 'bower_concat', 'uglify', 'cssmin', 'copy']);

};
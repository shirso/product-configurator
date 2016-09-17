/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    uglify: {
      dist: {
        options: {
          sourceMap: 'assets/js/map/source-map.js'
        },
        files: {
          'assets/js/wpc_plugins.min.js': [
            'assets/js/vendor/*.js',
          ],
          'admin/assets/js/wpc_admin_plugins.min.js': [
            'admin/assets/js/vendor/*.js',
          ],
          'admin/assets/js/wpc_admin_script.min.js': [
            'admin/assets/js/wpc.admin.js',
          ],
          'assets/js/wpc_frontend_script.min.js': [
            'assets/js/wpc_frontend_script.js'
          ]
        }
      }
    },
    jshint: {
      options: {
        reporterOutput: "",
        "force":true,
        "curly": false,
        "eqeqeq": false,
        "immed": true,
        "latedef": true,
        "newcap": true,
        "noarg": true,
        "sub": true,
        "undef": false,
        "unused": false,
        "boss": true,
        "eqnull": true,
        globals: {

        }
      },
      gruntfile: {
        src: 'Gruntfile.js'
      },
      lib_test: {
        src: ['assets/js/wpc_frontend_script.js','admin/assets/js/wpc.admin.js']
      }
    },

    watch: {
      gruntfile: {
        files: '<%= jshint.gruntfile.src %>',
        tasks: ['jshint:gruntfile']
      },
      lib_test: {
        files: '<%= jshint.lib_test.src %>',
        tasks: ['jshint:lib_test']
      }
    },
    cssmin: {
      dist: {
        options: {
          banner: '/*! MyLib.js 1.0.0 | Aurelio De Rosa (@AurelioDeRosa) | MIT Licensed */'
        },
        files: {
          'assets/css/wpc_frontend_style.min.css': ['assets/css/src/*.css']
        }
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  // Default task.
  grunt.registerTask('default', ['jshint', 'uglify', 'cssmin']);

};

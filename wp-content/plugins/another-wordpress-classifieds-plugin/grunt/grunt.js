/*global module: false*/
/*jslint indent: 2*/

module.exports = function(grunt) {
  grunt.awpcp.registerPluginTasks( {
    name: 'awpcp',
    slug: 'awpcp',
    path: 'another-wordpress-classifieds-plugin/resources',
    concat: {
      files: {
        '<%= path.awpcp %>/js/awpcp.src.js': [
          '<%= path.awpcp %>/js/legacy.js',
          '<%= path.awpcp %>/js/awpcp.js',
          '<%= path.awpcp %>/js/jquery.js',
          '<%= path.awpcp %>/js/knockout.js',
          '<%= path.awpcp %>/js/components/category-dropdown/*.js',
          '<%= path.awpcp %>/js/components/file-manager/*.js',
          '<%= path.awpcp %>/js/components/media-manager/*.js',
          '<%= path.awpcp %>/js/components/media-uploader/*.js',
          '<%= path.awpcp %>/js/components/media-center.js',
          '<%= path.awpcp %>/js/components/messages/*.js',
          '<%= path.awpcp %>/js/components/thumbnails-generator/*.js',
          '<%= path.awpcp %>/js/asynchronous-tasks.js',
          '<%= path.awpcp %>/js/collapsible.js',
          '<%= path.awpcp %>/js/localization.js',
          '<%= path.awpcp %>/js/settings.js',
          '<%= path.awpcp %>/js/users-autocomplete.js',
          '<%= path.awpcp %>/js/users-dropdown.js',
          '<%= path.awpcp %>/js/jquery-userfield.js',
          '<%= path.awpcp %>/js/jquery-collapsible.js',
          '<%= path.awpcp %>/js/jquery-validate-methods.js',
          '<%= path.awpcp %>/js/main.js',
          '<%= path.awpcp %>/js/recaptcha.js',
        ],
        '<%= path.awpcp %>/js/admin-pointers.src.js': [
          '<%= path.awpcp %>/js/components/pointers/pointers-manager.js',
          '<%= path.awpcp %>/js/admin/pointers.js',
          '<%= path.awpcp %>/js/admin/drip-autoresponder.js'
        ],
        '<%= path.awpcp %>/js/awpcp-admin.src.js': [
          '<%= path.awpcp %>/js/components/settings-validator.js',
        ],
        '<%= path.awpcp %>/js/components/plupload-queue/jquery-plupload-queue.src.js': [
          '<%= path.awpcp %>/js/components/plupload-queue/i18n.js'
        ]
      }
    },
    less: {
      files: {
        '<%= path.awpcp %>/css/awpcpstyle.css': '<%= path.awpcp %>/less/frontend.less',
        '<%= path.awpcp %>/css/awpcpstyle-ie-6.css': '<%= path.awpcp %>/less/frontend-ie6.less',
        '<%= path.awpcp %>/css/awpcpstyle-lte-ie-7.css': '<%= path.awpcp %>/less/frontend-lte-ie-7.less',
        '<%= path.awpcp %>/css/awpcp-admin.css': '<%= path.awpcp %>/less/admin.less'
      }
    }
  } );
}

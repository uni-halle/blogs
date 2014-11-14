/*global module: false*/
/*jslint indent: 2*/

module.exports = function(grunt) {
  grunt.awpcp.registerPluginTasks( {
    name: 'awpcp',
    slug: 'awpcp',
    path: 'another-wordpress-classifieds-plugin/resources',
    concat: {
      src: [
        '<%= path.awpcp %>/js/knockout-3.1.0.min.js',
        '<%= path.awpcp %>/js/legacy.js',
        '<%= path.awpcp %>/js/awpcp.js',
        '<%= path.awpcp %>/js/jquery.js',
        '<%= path.awpcp %>/js/knockout.js',
        '<%= path.awpcp %>/js/components/file-manager/*.js',
        '<%= path.awpcp %>/js/components/category-dropdown/*.js',
        '<%= path.awpcp %>/js/asynchronous-tasks.js',
        '<%= path.awpcp %>/js/collapsible.js',
        '<%= path.awpcp %>/js/localization.js',
        '<%= path.awpcp %>/js/settings.js',
        '<%= path.awpcp %>/js/users-autocomplete.js',
        '<%= path.awpcp %>/js/users-dropdown.js',
        '<%= path.awpcp %>/js/jquery-userfield.js',
        '<%= path.awpcp %>/js/jquery-collapsible.js',
        '<%= path.awpcp %>/js/main.js',
      ],
      dest: '<%= path.awpcp %>/js/awpcp.src.js'
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

=== LePress Teacher ===
Contributors: raido357, vtomberg
Tags: Assessment, assignments, course, teacher, student
Requires at least: 3.0
Tested up to: 3.0.4
Stable tag: 1.06

LePress is plugin for teachers/students, who use WordPress for their daily learning and teaching tasks like organizing courses and making assessments.

== Description ==

LePress is an open-source plugin for Wordpress, which turns Wordpress into lightweight, distributed and personalised Learning Management System.
 
Teacher can use his/her LePress blog for setting up an online course, inviting/enrolling students to the course, managing and grading assignments. Every student should have his/her own LePress-enhanced blog in order to participate in such course. Enrolled students can follow the announcements of new assignments with help of LePress sidebar widget, submit their assignments as blog posts and receive feedback from the teacher in the form of comment. Assignment-related blog posts are tagged with dedicated category, allowing easy filtering. Both teacher and students can use the same blog for participating in several blog-based courses simultaneously. 

The teachers can track, review and grade the submissions of the students conveniently using an aggregated summary page, just the same ways as they do it in a traditional grade book.
LePress supports the use of microformats for sharing the assignments (hCal) and contacts of course participants (hCard). This information can be imported right into the user's contacts- or time-management systems.

LePress plugin has two separate distributives, LePress Teacher and LePress Student, they must be installed accordingly in each blog instance of the teacher and students who want participate in the course. You can download LePress Student plugin [here](http://wordpress.org/extend/plugins/lepress-student/ "LePress Student"). If you use WordPress 3 in multiuser mode and want share it between students and teachers, you need install both version of plugin.

== Installation ==

For Teachers.

1. Install LePress Teacher plugin. You can Activate LePress widget right after plugin installation. Please do not activate LePress "Sitewide" if you use WordPress 3.X in multiuser mode, just choose "activate plugin".

2. In dashboard menu: LePress >> Courses create new course or mark existing category as a course. Do not forget to fill in information about teacher, in other case course will not work.

3. Choose LePress >> Subscriptions menu and invite students to the specific course/s. You can import a list of students" email addresses from a file or fill it manually. You can accompany the invite with an individual or group security key. In such way you can filter the course from unwanted subscribers because the subscription cannot be implemented without key.

4. In Write new assignment menu you can prepare assignment-post. Choose a corresponding course, start date, and deadline for assignment. Deadline dates will be shown in calendars of LePress widget both in the teacher's and student's blogs.

5. The Manage assignments page is your class-book. Here you can see a list of all the implemented assignments, graduate them and write the feedbacks. You can access this page right from widget as well. Your feedback will appear as a standard WordPress comment in blog of the student.

6. Please consider that using LePress Teacher plugin has sense only if somebody uses LePress Student version.

For Students.

1. Install LePress Student plugin. You can Activate LePress widget right after plugin installation. Please do not activate LePress "Sitewide" if you use WordPress 3.X in multiuser mode, just choose "activate plugin".

2. After receiving the email with invite from teacher, point to WordPress Dashboard >> LePress Student >> Subscriptions, copy received course address and Invite key (if any) and paste them into form. Press Subscribe button. After some times (it can be varied between WordPress installations) you will be subscribed.

3. Track new assignments in the widget and implement them on the LePress Student >> Assignments page.
Your submission will be shown as the regular blog posts in your blog and as the comments in the assignment post in teacher"s blog.

4. Please consider that using LePress Student plugin has sense only if somebody uses LePress Teacher version.


NB! Do not use "sitewide" activation on network installations, it will fail, because both plugins use extra database tables, which are not created via "sitewide" activation.

== Screenshots ==

1. Creating new course in LePress Teacher dashboard.
2. Opening new assignment in LePress Teacher dashboard.
3. LePress widget installed into blog.
4. Available assignments in LePress Student dashboard.
5. Gradebook in LePress Teacher dashboard. gradebook.
6. Importing microformats with course information in browser. In current example Google Chrome with Micromeformats extension is shown. Other microformat solutions also are available for different browser platforms.

== Changelog ==

= 1.06 =
* More accurate ungraded assignments counting

= 1.05 =
* Fix sending feedback trackback to students blog

= 1.04 =
* Add force decline option on subscriptions page

= 1.03 =
* Minor fixes

= 1.02 =
* Fix some JS/CSS loading issues

= 1.01 =
* Added submission time field to "Manage assignments" page, enables teacher to track if student submitted his/her work on time or not.

= 1.0 =
* First release

== Upgrade Notice ==

= 1.06 =
More accurate ungraded assignments counting

= 1.05 =
Fix sending feedback trackback to students blog

= 1.04 =
Add force decline option on subscriptions page

= 1.03 =
Minor fixes

= 1.02 =
Fix some JS/CSS loading issues, please upgrade. Upgrading will delete your previous data.

= 1.01 =
Added submission time field to "Manage assignments" page, enables teacher to track if student submitted his/her work on time or not.

= 1.0 =
First release
Please find below the steps to get the application ready

1. Install drupal 8.5.x
2. composer update
3. composer install
3. composer require google/apiclient:~2.0
4. composer global require drush/drush:8.*
5. place sync folder in site/default/files/config/sync
6. replace modules folder in drupal root with the modules folder here
7. drush cim -y

this should work else after step 4 above

1. install youtube module
2. Add content type with machine name 'youtube_videos'
3. Add fields as:
    video field of type youtube : machine name - 'field_trending_video'
    Taxonomy term reference field: machine name - 'field_tag'
4. place trending_youtube inside modules/custom and install the module.

Now to navigate to import videos page go to admin/config under content authoring you'll
find import video link, click there and there you go.

please give me  call in case of any discrepancy @9988424767
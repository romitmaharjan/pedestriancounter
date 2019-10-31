Step 1:
Open xampp control, start Apache and MySQL

Step 2:
extract the zip file in the folder, xampp/htdocs

Step 3:
Open up localhost/phpmyadmin and import the database named pedestrian_counter.sql

Step 4:
Open up a text editor, and add the api key on file "index.php" where it says "apikey"
on the line 
<script async defer src="https://maps.googleapis.com/maps/api/js?key="apikey"&libraries=visualization&callback=myMap
"></script>
which is provided on the submission file

Step 5:
Open browser and type in localhost/pedestriancounter

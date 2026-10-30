<?php

/*
 * Assign the supplied portraits to people.
 *
 * The 35 portraits in the ZIP are not labelled with names, so they could not be matched
 * automatically (guessing who is who would be wrong). They are all imported into the
 * photo library (public/people/library) and can be picked from the
 * "Profile photo" field of any profile in Admin → Board Members.
 *
 * To assign many at once, fill this map and run:   php artisan db:seed --class=CommitteePhotoSeeder
 *
 *   '<ref from committee.php>' => '<portrait file name>',
 *
 * e.g.   '5' => 'portrait-7.jpg',          // Balmukunda Prajapati (Chairperson)
 *        'conv-loan' => 'portrait-7.jpg',  // the same photo may be used for several rows
 */

return [
    // '5' => 'portrait-7.jpg',
];

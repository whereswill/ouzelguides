<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Notes";

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

<p><a class="btn btn-primary" href="<?php echo $previous?>">Back</a></p>

<blockquote>
<p>Right now John, Will, and Brian are the only admins and don't show up in users. There is also an admin user (admin.user admin123). Admin can do everything. Users can only see their profile and the comments wall. Editors can add comments to the comments wall.</p>
</blockquote>

<blockquote>
<p>Dont know if you pay full shop pay for a Rog Combo. Right now shop pay takes 15 x #days and divides by shoppers. You will have to adjust manually.</p>
</blockquote>

<blockquote>
<p>I'm still working on how to activate and de-activate users in the user drop-downs. right now, every user is active.</p>
</blockquote>

<blockquote>
<p>Guide settings are off. Base pay should be the same as the example payroll as of July last year. Certs, Hire date, and cert expirations need to be corrected.</p>
</blockquote>

<blockquote>
<p>User Names are the guides first and last names like will.sharp all lower case. The passwords are the same. They should verify their email and add their other details. They should change their password as soon as they log-in.</p>
</blockquote>

<blockquote>
<p>You can't add or delete rivers (drainages), they are hard-coded.</p>
</blockquote>

<blockquote>
<p>Based on Brians instruction, the trips are included in a pay period based on put-in date rather than take-out date. this can be configured pretty easily.</p>
</blockquote>

<blockquote>
<p>There are no pay periods. Instead you define the end dates when you pay approved work. The idea is that you choose the end date of the pay period. The beginning date is theoretically irrelevant because you will just pay any approved work before the chosen end date anyway.</p>
</blockquote>
<blockquote>
<p>Swampers are only withheld base pay by the app. If you check TL, rigger, or shopper, they will be paid for those items. They will also be paid for certs and they will accumulate bonus pay</p>
</blockquote>

<blockquote>
<p>Guide Roles (not Other Roles) are hard-coded. Let me know if they need to be editable or have some pay parameters attached to them.</p>
</blockquote>

<blockquote>
<p>Any text input labeled "notes" will be printed on the pay stub so keep it short.</p>
</blockquote>

<blockquote>
<p>Approving a trip will only approve work from the TL Worksheet. Warehouse work associated with a trip will not be approved when the trip is approved. Timesheets and Trips are seperate entities and are approved seperately</p>
</blockquote>

<blockquote>
<p>Food Shop Pay is too convoluted. It will need to be the manual entry of the amount in this iteration. How important is it to you that this function be nice?</p>
</blockquote>

<blockquote>
<p>Role types come in 2 flavors and they determine where they appear in scheduling drop-downs. "Guide" roles are for boatmen and appear when scheduling guides. "Other" roles are for work not associated with a trip directly. Both the latter roles are for jobs that may or may not be associated with a trip.</p>
</blockquote>

<blockquote>
<p>The active checkbox for users and guides simply adds them to the drop-downs on the scheduling screens and reports. It only reflects the current state - work that happened last month will not reflect those who were active or inactive at that time. You can have a currently inactive person already scheduled for work.</p>
</blockquote>

<blockquote>
<p>I decided not to add a way to push the rig amount. This will be done in adjustment initially and we can see how much of a burden this is. I only saw 1 paysheet that took advantage of this so the adjustment function might suffice. Don't let guides put in a percentage - make them divvy up the amount. That will save you some math.</p>
</blockquote>

<blockquote>
<p>Daily Doubles are the second trip of a 2 trip series. the first needs to be entered as a regular 1/2 day. This should work for Santiam trips too. A TL who was TL on the first trip will not get TL pay but if the TL switches, both will get TL pay. </p>
</blockquote>

<blockquote>
<p>Current Bonus Amount only takes into account paid trips. Approved trips that haven't been paid are not added in the figure.</p>
</blockquote>

<blockquote>
<p>"Locking" and "Paying" are the same thing. It is assumed that when you lock the trips and timesheets up to a given End Date, you will also print and turn in the payrol to be paid. "Printing" and "Paying" ar NOT the same thing. you can print payroll for a particular lock date as often as you want. </p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Add hours and rate(default to 9.00) functionality to adding wh work.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Create a guide certs page for admins to see all and whether they are current or expired for FA and CPR.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Add number of guests to trip and create report for guide/guest ratio for trip and per river.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Create a printable river log page for guides.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Create pages for guides to see their pay stubs and trips that they were on that have been paid.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Allow guides to edit their own guide profile page except for feilds that they shouldn't touch.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Assume identity of another user???.</p>
</blockquote>

<blockquote>
<p>POSSIBLE ENHANCEMENT Swampers report. Who has swamped what. Could even add a check off feature. Which could lead to an eligibility feature.</p>
</blockquote>

<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>
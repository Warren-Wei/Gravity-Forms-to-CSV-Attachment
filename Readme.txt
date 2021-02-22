TO UPDATE TO SPECIFIC FORM:

Edit add_function and include ID. Example, change all forms to form 11 only -

add_action( 'gform_after_submission', 'populate_gravity_csv', 10, 2 );

To

add_action( 'gform_after_submission_11', 'populate_gravity_csv', 10, 2 );
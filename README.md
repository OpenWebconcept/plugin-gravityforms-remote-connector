# How to use the IRMA-WP plugin
1. Go to the plugin page and activate the plugin called: IRMA-WP for Wordpress.

2. Set up the IRMA configuration this can be done in the Wordpress dashboard. Look for the menu-item 'IRMA'. 
    2a. For the inputfield 'Server endpoint' you must fill in: https://metrics.privacybydesign.foundation/irmaserver (this URL will change in the future)

3. There a 2 ways to setup a form with IRMA implementation
    3a. While creating a form go to the metabox 'Advanced fields' and pick the fields 'IRMA-attribute' & 'IRMA-launch-QR'.
        For the 'IRMA-attribute' field you must fill in the name of the attribute you want to retrieve. Example: irma-demo.nijmegen.bsn.bsn
        You can use multiple 'IRMA-attribute' fields. This is all you need to do.
    3b. The steps in step 3a can also be combined in one advanced field. The name of this field is 'IRMA-header'. This field has a few           settings. The fields 'ID attribute fullname', 'ID attribute BSN', 'ID attribute city' need to contain names of the attributes you        want to retrieve. The only field that is required is the 'ID attribute BSN'. 

4. After completing step 3 and a substep (A or B) the form is ready for use.

# To-do
- In the plug-in some of the validations are done with fixed value's. These value's need to be configurable and then dynamically placed in     the code. The following files contains fixed value's: plugins/irma-wp/src/IRMA/GravityForms/IrmaHeaderField.php (line 84).
- In the plugin.php there is a constant 'IRMA_WP_RSIN_BUREN' defined specific for the 'gemeente buren'. 
  This value needs to be configurable. Path to file:plugins/irma-wp/plugin.php (line 22).

# Keep in mind
- In the file plugins/irma-wp/config/core.php there are multiple configurable settings. In the return array there is a key 'caseProperties' with unique references in it. These references need to match with Decos for passing through values to their API. The references are needed for receiving the right values


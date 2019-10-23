# How to use the IRMA-WP plugin

1. Go to the plugin page and activate the plugin called: IRMA-WP for Wordpress.

2. Set up the IRMA configuration this can be done in the Wordpress dashboard. Look for the menu-item 'IRMA'.
   2a. For the inputfield 'Server endpoint' you must fill in: https://metrics.privacybydesign.foundation/irmaserver (this URL will change in the future).

    2b. For the inputfield 'RSIN' you must fill in the RSIN number of your organisation.
    More information on this topic can be found on: https://www.kvk.nl/over-kvk/over-het-handelsregister/wat-staat-er-in-het-handelsregister/nummers-in-het-handelsregister/rsin-nummer/

    2c. For the inputfields 'create case URL', 'create case object URL' and 'create case property URL' you must fill in the right URL's that your organisation is using for NLX.
    For inputfield 'create case property URL' the following format is required 'https://buren-nlx-outway.yard.nl/Gemeente-Buren/DecosJoinV2/zaken/caseID/zaakeigenschappen'. The caseID is a wildcard. This plug-in will handle the rest because a caseID is generated after the first api call to NLX. The URL in this inputfield will be used from the third api call.

    2d. For every field that you want to use for the API call to Decos you must fill in a unique caseProperty ID. You can add these Decos attributes in the 'Overview' section on the settings page.
    The name of a caseProperty is free for you to give, it's important that the casePropertyValue corresponds with the exact same value that is used by Decos.

    Currently known fields that can be used are listed below:
    36EAA892-CDD8-4A46-B757-961C1941EE0E    Ingangsdatum(NL) / starting date(EN)
    7DE283A3-B895-42AE-9163-DF1E1ED60460    Kenneltarief(NL) / Hokrate(EN)
    9B8FBE5C-A40B-4AE2-B0BA-BA493BB425C4    Aantal honden na aanmelding(NL) / Total number of dogs after registration (EN)

    Fields without a casePropertyName will not be used in the API call.

    2e. (IMPORTANT) If you are using a validation field like the IRMA-header or IRMA-attribute you MUST fill in 'BSN' as the value for the caseProperty(EN)/Case eigenschap(NL) field.

3. There a 2 ways to setup a form with IRMA implementation
   3a. While creating a form go to the metabox 'Advanced fields' and pick the fields 'IRMA-attribute' & 'IRMA-launch-QR'.
   For the 'IRMA-attribute' field you must fill in the name of the attribute you want to retrieve. Example: irma-demo.nijmegen.bsn.bsn
   You can use multiple 'IRMA-attribute' fields. This is all you need to do.
   3b. The steps in step 3a can also be combined in one advanced field. The name of this field is 'IRMA-header'. This field has a few settings. The fields 'ID attribute fullname', 'ID attribute BSN' and 'ID attribute city' need to contain names of the attributes you want to retrieve. The only field that is required is the 'ID attribute BSN'.

4. After completing step 3 and a substep (A or B) the form is ready for use.

# Need for help?

-   If you're stuck with setting up a form or did you find a bug you can contact Yard Internet via mike@yard.nl

# To-do/issues

-   Creating an IRMA-session from the Yard server is not working yet because of a unauthorized token issue.

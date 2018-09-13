# VA Demo Helper
This module provides helper functions for the VA Demo site. It is also used to hold content exported by the _Default Content_ module.

### Exporting default content
1. Create content and users via the UI.
1. Get the _UUID_ for the content and users
    1. run `drush sqlq 'select uuid from node where 1'`
    1. run `drush sqlq 'select uuid from users where uid > 1'`
1. Copy the UUIDs to the **vademo_helper.info.yml** file. For example:
    ~~~~
    default_content:  
      node:
        - 7bbac1dd-baf1-4935-923a-923388d48d65
      user:
        - 8d3674e0-ae8e-451f-937f-b8b758acb183
    ~~~~
1. Once you have added the UUIDs to the _.info.yml_ file, run `drush dcem vademo_helper` to create the **json** files that will be used to create the content. The files will live in the `/content/node/` and the `/content/user/` directories within this module.
1. The content and users will be created the next time that the site is built.
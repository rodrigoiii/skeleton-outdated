# Skeleton Auth

## Need to install manually

 - In the root of project, run `npm install --save-dev bootstrap jquery`.
 - In the public folder, run `yarn add bootstrap-sass jquery-validation`.
 - In sponge.config.js, append the following in `scripts.entries_array`:
   - "auth",
   - "auth/register",
   - "auth/login",
   - "auth/account-setting",
   - "auth/forgot-password",
   - "auth/reset-password",
   - "auth/jquery-validation/add-methods"

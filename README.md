Secured login system with employee listing -Ravikumar P S 
---------------------------------------------------------

Following are the step by step procedure followed in this test...

1. Downloaded the latest Symfony 2.7 project.
2. Setting up the configurations including the parameter.yml
3. Download the FOS USERBUNDLE to handle the authentication and registration.
4. Configured the Fos User Bundle in the application
5. Generate entities and update schema.
6. Override the Registration, Security and Restting controllers from Fos User Bundle to customize according to our needs.
7. Customized the default routing for login, registration, reset and logout.
8. Changed the Access Control in the security.yml file.
9. Registration and listing pages are restricted to Admin with role ROLE_ADMIN.
10.Listing for both active and resigned employees are done.
11.In the active employee listing itself, set an option to make the employee resign.
12.Filtering with date for both active and resigned employees are done.
13.Implemented separate layout for both login related pages and listing pages.
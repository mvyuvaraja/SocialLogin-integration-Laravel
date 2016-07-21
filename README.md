# SocialLogin-integration-Laravel

# Migration file
  located in app/database/migrations/

#To achieve multiple channel login for same user account
Create one more table "user_credentials" (refer migration table script). All login details of a user will be stored here.
We can add n number of social media logins without affecting code by this method. (I hope).

#Test case steps:
1. Register an account by any channel.
2. Add all other social media accounts once you are logged in.
3. Logut & try login.

#Drawback:
If you don't add your social media accounts inside the logged in page, all account used in login screen will be created separate user account.


  
# Social login plugin : 

  Refer the URL https://github.com/artdarek/oauth-4-laravel. 
  It helps to integrate Facebook, Google, Twitter login.
  
#If you want to add additional Channel:
  You don't need to alter table structure & Coding.
  Just need to add one more option in the enumerated column registered_from in user_credentials table

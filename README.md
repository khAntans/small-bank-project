# Small bank project
Small bank made with Laravel 10. Allows the user to create debit and investment account, send money and view transaction history.

## How to run it
- Do `composer install`
- Copy the .env.example file and rename it to .env and set the appropriate db parameters.
- Run `php artisan key:generate` to generate the app key
- Run the migrations `php artisan migrate`
- `npm install` to install the js dependencies
- Open a new terminal and run `php artisan schedule:work` which will fetch new currency data every 5 minutes.
- In another terminal window/tab run `npm run dev` 
- And at last run `php artisan serve` and click on the provided localhost link to access the application.

### Images
#### Index
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2014-34-14.png?token=GHSAT0AAAAAACMPTQE3P7EZFCXP2XRGAORMZNQBFXQ" alt="index page"><br>
#### First time user dashboard view
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2015-46-12.png?token=GHSAT0AAAAAACMPTQE2FCZWEZ7K45HKWIZ6ZNQBH2Q"><br>
#### Repeat users dashboard view
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2014-36-26.png?token=GHSAT0AAAAAACMPTQE3M7JFLLSDNEKRR3KSZNQBHDQ" alt="Repeat users dashboard view"><br>
#### First time user investment tab view
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2014-35-10.png?token=GHSAT0AAAAAACMPTQE2ASFLMYCRJBQYESJUZNQBGEQ" alt="First time user investment tab view"><br>
#### Repeat users investment tab view
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2014-36-42.png?token=GHSAT0AAAAAACMPTQE2SOVVP6UX7454ZBXGZNQBHKQ" alt="Repeat users investment tab view"><br>
#### Transactions
<img src="https://raw.githubusercontent.com/khAntans/images-for-personal-projects/main/Screenshot%20from%202024-01-22%2014-37-53.png?token=GHSAT0AAAAAACMPTQE3GMKYWDE7YFTZD3VQZNQBHRA" alt="Transactions"><br>

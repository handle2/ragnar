/**
 * Created by Krisz on 2017.05.01..
 */
/// <reference path="./../../../../typings/tsd.d.ts" />
module frontApp {
    interface IRegisterController{
        register();
    }

    class RegisterController implements IRegisterController{


        public _formData = {
            username: null,
            password: null,
            passwordAgain: null
        };

        public error = "";

        constructor(private root , private scope, private window, public registerService,public translate,public http) {

        }

        public register(){
            var self = this;
            if(this._formData.password == null){
                this.error = "A jelszó kitöltése kötelező!";
                return false;
            }

            if(this._formData.password != this._formData.passwordAgain){
                this.error = "A két jelszónak meg kell egyeznie!";
                return false;
            }
            if(this._formData.username == null){
                this.error = "A felhasználónév kitöltése kötelező!";
                return false;
            }

            this.http.post('/register/send',this._formData).then(function (data) {
                self.window.open('/', '_self');
            });

        }

    }

    var frontApp = angular.module('frontApp');
    frontApp.controller('RegisterController', ['$rootScope','$scope','$window', 'RegisterService','$translate','$http', RegisterController]);
}
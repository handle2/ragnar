/**
 * Created by Krisz on 2017.05.01..
 */
/// <reference path="./../../../../typings/tsd.d.ts" />
module frontApp {
    interface ILoginController{
        login();
    }

    class LoginController implements ILoginController{


        public _formData = {
            username: null,
            password: null
        };

        public error = "";

        constructor(private root , private scope, private window ,public translate,public http) {

        }

        public login(){

            this.http.post('/login/enter',this._formData).then(function (data) {
                self.window.open('/', '_self');
            });

        }

    }

    var frontApp = angular.module('frontApp');
    frontApp.controller('LoginController', ['$rootScope','$scope','$window','$translate','$http', LoginController]);
}
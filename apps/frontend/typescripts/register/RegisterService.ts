/// <reference path="./../../../../typings/tsd.d.ts" />

module frontApp{
    interface IRegisterService{

    }
    export class RegisterService implements IRegisterService{

        constructor(private rootScope,private location,private window,private http,private localStorageService){


        }

    }

    var frontApp = angular.module('frontApp');


    frontApp.service('RegisterService', ['$rootScope','$location','$window','$http','localStorageService', function(rootScope,location,window,http,localStorageService){
        return new RegisterService(rootScope,location,window,http,localStorageService);
    }]);
}

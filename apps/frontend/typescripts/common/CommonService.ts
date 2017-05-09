/// <reference path="./../../../../typings/tsd.d.ts" />

module frontApp{
    import IHttpPromise = angular.IHttpPromise;
    interface IUser{
        username:string;
        email: string;
        group: number;
        id:number;
        level : number;
        password: string;
        role : any;
    }
    interface ICommonService{
        getLoggedUser():void;
        hasPermission(code:string):boolean;
    }
    export class CommonService implements ICommonService{
        public user : IUser;
        public langs;

        public static instance : CommonService;
        constructor(private rootScope,private location,private window,private http,private localStorageService){

            if(!this.user){
                //this.reloadUserData();
            }
            //this.getLangs();
        }


        public getLoggedUser(){
            return this.http.get('/admin/profile/getUser');
        }

        public reloadUserData(){
            var self = this;
            this.getLoggedUser().then(function (response) {
                self.user = response.data;
            });
        }

        public hasPermission(code){
            if(this.user && !this.user['$$state']){
                return this.user.role.rights.indexOf(code)>-1?true:false;
            }
            return false;

        }

        private getLangs(){
            var self = this;
            this.http.get('/admin/language/getLangs').then(function (response) {
                self.langs = angular.fromJson(response.data);
            });
        }
    }

    var frontApp = angular.module('frontApp');


    frontApp.service('CommonService', ['$rootScope','$location','$window','$http','localStorageService', function(rootScope,location,window,http,localStorageService){
        return new CommonService(rootScope,location,window,http,localStorageService);
    }]);
}

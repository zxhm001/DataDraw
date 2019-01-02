 var mxSettings = {
    settings:{
        libraries: Sidebar.prototype.defaultEntries
    },

    getLibraries:function(){
        return mxSettings.settings.libraries;
    },

    setLibraries:function(libs){
        mxSettings.settings.libraries = libs;
    }
};
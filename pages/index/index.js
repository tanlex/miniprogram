//index.js
//获取应用实例
const app = getApp()



Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
      name: 'WeChat'
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  changeName: function(e) {
      // sent data change to view
      this.setData({
          name: 'MINA'
      })
  },
  changeClick: function(e) {

      wx.login({
          success: res => {
              // 发送 res.code 到后台换取 openId, sessionKey, unionId
              console.log(res);
              wx.request({
                  url: 'https://mini.poxx.top/app/controller/index.php?act=service_send',
                  data: {
                      code : res.code
                  },
                  header: {
                      'content-type': 'application/json' // 默认值
                  },
                  success (res1) {
                      console.log(res1.data)


                  }
              })

          }
      })


  }



})



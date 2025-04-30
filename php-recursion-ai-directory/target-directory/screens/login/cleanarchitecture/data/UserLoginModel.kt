package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class UserLoginModel(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: UserLoginDataModel? = UserLoginDataModel(),
)

data class UserLoginDataModel(
    @SerializedName("is_logged_in" )
    var isLoggedIn: Boolean? = null,
    @SerializedName("data")
    var data: UserLoginDataItemModel? = UserLoginDataItemModel(),
)

data class UserLoginDataItemModel(
    @SerializedName("access_token")
    var accessToken: String? = null,
    @SerializedName("refresh_token")
    var refreshToken: String? = null
)

/*
data class UserLoginModel(
    val userAuthToken: String?,
    val isLoggedIn: Boolean,
)*/

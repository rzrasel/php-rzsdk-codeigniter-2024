package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class UserLoginDto(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: UserLoginDataDto? = UserLoginDataDto(),
)

data class UserLoginDataDto(
    @SerializedName("is_logged_in" )
    var isLoggedIn: Boolean? = null,
    @SerializedName("data")
    var data: UserLoginDataItemDto? = UserLoginDataItemDto(),
)

data class UserLoginDataItemDto(
    @SerializedName("access_token")
    var accessToken: String? = null,
    @SerializedName("refresh_token")
    var refreshToken: String? = null
)

/*
data class UserLoginDto(
    @SerializedName("user_auth_token")
    val userAuthToken: String?,
    @SerializedName("is_logged_in")
    val isLoggedIn: Boolean,
)*/

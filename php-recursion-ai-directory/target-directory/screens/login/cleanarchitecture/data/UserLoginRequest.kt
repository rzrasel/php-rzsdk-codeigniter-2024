package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class UserLoginRequest(
    @SerializedName("email")
    var email: String,
    @SerializedName("password")
    var password: String,
)
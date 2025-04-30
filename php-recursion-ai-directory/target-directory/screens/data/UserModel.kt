package com.rzrasel.wordquiz.presentation.screens.data

import kotlinx.serialization.Serializable

@Serializable
data class UserModel(
    var userAuthToken: String?,
    var isLogin: Boolean = false,
)
package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class DashboardRequest(
    @SerializedName("user_auth_token")
    val userAuthToken: String?,
)
package com.rzrasel.wordquiz.presentation.screens.state

sealed class LoginUiEventOld {
    data class EmailOrMobileChanged(val inputValue: String): LoginUiEventOld()
    data class PasswordChanged(val inputValue: String): LoginUiEventOld()
    data object Submit: LoginUiEventOld()
}
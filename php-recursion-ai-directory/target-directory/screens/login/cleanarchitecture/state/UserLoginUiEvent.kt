package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state

sealed class UserLoginUiEvent {
    data class EmailOrMobileChanged(val inputValue: String): UserLoginUiEvent()
    data class PasswordChanged(val inputValue: String): UserLoginUiEvent()
    data object Submit: UserLoginUiEvent()
}
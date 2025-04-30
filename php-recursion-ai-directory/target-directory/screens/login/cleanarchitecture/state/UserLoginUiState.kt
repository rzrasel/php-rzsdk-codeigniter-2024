package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginModel

sealed class UserLoginUiState {
    data object Idle: UserLoginUiState()
    data object Loading: UserLoginUiState()
    data class Success(val user: UserLoginModel): UserLoginUiState()
    data class Error(val message: String): UserLoginUiState()
}
package com.rzrasel.wordquiz.presentation.screens.state;

sealed class UserRegistrationUiState {
    data object Idle: UserRegistrationUiState()
    data object Loading: UserRegistrationUiState()
    data object Success: UserRegistrationUiState()
    //data object Error: UserRegistrationUiState()
    data class Error(val message: String) : UserRegistrationUiState()
}
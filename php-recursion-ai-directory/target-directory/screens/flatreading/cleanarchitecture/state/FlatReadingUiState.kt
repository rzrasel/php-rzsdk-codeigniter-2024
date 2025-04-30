package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingModel

sealed class FlatReadingUiState {
    data object Idle: FlatReadingUiState()
    data object Loading: FlatReadingUiState()
    data class Success(val flatReadingModel: FlatReadingModel) : FlatReadingUiState()
    data class Error(val message: String): FlatReadingUiState()
}
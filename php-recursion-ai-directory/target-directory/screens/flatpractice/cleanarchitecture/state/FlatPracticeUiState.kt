package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeModel

sealed class FlatPracticeUiState {
    data object Idle: FlatPracticeUiState()
    data object Loading: FlatPracticeUiState()
    data class Success(val flatPracticeModel: FlatPracticeModel) : FlatPracticeUiState()
    data class Error(val message: String): FlatPracticeUiState()
    data class Result(val message: String): FlatPracticeUiState()
}
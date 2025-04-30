package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel

sealed class DashboardUiState {
    data object Idle: DashboardUiState()
    data object Loading: DashboardUiState()
    data class Success(val dashboardModel: DashboardModel) : DashboardUiState()
    data class Error(val message: String): DashboardUiState()
}
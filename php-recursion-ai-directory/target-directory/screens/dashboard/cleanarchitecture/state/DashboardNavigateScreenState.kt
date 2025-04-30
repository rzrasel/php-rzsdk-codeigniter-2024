package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.state

sealed class DashboardNavigateScreenState {
    data object Idle: DashboardNavigateScreenState()
    data class FlatReading(val isNavigate: Boolean = false): DashboardNavigateScreenState()
    data class FlatPractice(val isNavigate: Boolean = false): DashboardNavigateScreenState()
}
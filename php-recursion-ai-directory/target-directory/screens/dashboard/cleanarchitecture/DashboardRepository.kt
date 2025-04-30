package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardDto
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardRequest

interface DashboardRepository {
    suspend fun getDashboard(dashboardRequest: DashboardRequest): NetworkResult<DashboardDto>
}
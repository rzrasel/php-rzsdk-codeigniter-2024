package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import android.util.Log
import com.rz.logwriter.DebugLog
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardDto
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardRequest
import retrofit2.HttpException
import java.io.IOException

class DashboardRepositoryImpl(private val apiService: DashboardApiService): DashboardRepository {
    override suspend fun getDashboard(dashboardRequest: DashboardRequest): NetworkResult<DashboardDto> {
        NetworkResult.Loading("")
        return try {
            //val response = apiService.getDashboard(dashboardRequest)
            val response = apiService.getDashboard()
            //preferences.saveAuthToken(response.token)
            //Log.i("DEBUG_LOG", "Log response code ${response.code()}")
            DebugLog.Log("Log response code ${response.code()}")
            if(response.isSuccessful) {
                DebugLog.Log("Log response successBody ${response.body().toString()}")
                NetworkResult.Success(response.body())
            } else {
                try {
                    //Log.i("DEBUG_LOG", "Log response errorBody ${response.errorBody().toString()}")
                    DebugLog.Log("Log response errorBody ${response.errorBody().toString()}")
                    NetworkResult.Error(response.errorBody().toString())
                } catch (e: Exception) {
                    //Log.i("DEBUG_LOG", "Log response Exception ${response.errorBody().toString()}")
                    DebugLog.Log("Log response Exception ${response.errorBody().toString()}")
                    NetworkResult.Error(e.message)
                }
            }
        } catch (e: IOException){
            //Log.i("DEBUG_LOG", "Log response IOException error ${e.message}")
            DebugLog.Log("Log response IOException error ${e.message}")
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            //Log.i("DEBUG_LOG", "Log response HttpException error ${e.message}")
            DebugLog.Log("Log response HttpException error ${e.message}")
            NetworkResult.Error("${e.message}")
        }
    }
}
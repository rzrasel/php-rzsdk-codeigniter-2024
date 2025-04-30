package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.rz.logwriter.LogWriter
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.state.DashboardUiState
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class DashboardViewModel(private val dashboardUseCase: DashboardUseCase): ViewModel() {
    private val _uiState = mutableStateOf<DashboardUiState>(DashboardUiState.Idle)
    val uiState: State<DashboardUiState> = _uiState
    private val _dashboardModel = MutableLiveData<DashboardModel?>()
    val dashboardModel: LiveData<DashboardModel?> get() = _dashboardModel

    fun getDashboard(userAuthToken: String? = null) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = DashboardUiState.Loading
            val dashboardResult = dashboardUseCase.execute(userAuthToken)
            when(dashboardResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    //Log.i("DEBUG_LOG", "Loading Data ${dashboardResult.data}")
                    LogWriter.log("Loading Data ${dashboardResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    //Log.i("DEBUG_LOG", "Success Data ${dashboardResult.data}")
                    LogWriter.log("Success Data ${dashboardResult.data}")
                    //_dashboardModel.value = dashboardResult.data
                    _dashboardModel.postValue(dashboardResult.data)
                    dashboardResult.data?.let {
                        //_dashboardModel.value?.userAuthToken = it.userAuthToken
                        _uiState.value = DashboardUiState.Success(it)
                    } ?: run {
                        _uiState.value = DashboardUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    //Log.e("DEBUG_LOG", "Error Data ${dashboardResult.data}")
                    LogWriter.log("Error Data ${dashboardResult.data}")
                    dashboardResult.message?.let {
                        _uiState.value = DashboardUiState.Error(it)
                    } ?: run {
                        _uiState.value = DashboardUiState.Error("Unknown error")
                    }
                }
            }
        }
    }
}
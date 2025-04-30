package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import android.util.Log
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state.FlatReadingUiEvent
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state.FlatReadingUiState
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class FlatReadingViewModel(private val flatReadingUseCase: FlatReadingUseCase): ViewModel() {
    //private val _uiState = mutableStateOf<AppFlatUiState>(AppFlatUiState.Idle)
    //val uiState: State<AppFlatUiState> = _uiState
    private val _uiState = MutableStateFlow<FlatReadingUiState>(FlatReadingUiState.Idle)
    val uiState: StateFlow<FlatReadingUiState> = _uiState
    //
    fun getFlatReadingInitial(userAuthToken: String? = "", categoryId: String, itemId: String, currentIndex: Int = 0) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = FlatReadingUiState.Loading
            val flatReadingResult = flatReadingUseCase.execute(userAuthToken, categoryId, itemId, currentIndex)
            when(flatReadingResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Loading Data ${flatReadingResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Success Data ${flatReadingResult.data}")
                    //_dashboardModel.value = dashboardResult.data
                    //_dashboardModel.postValue(flatReadingResult.data)
                    flatReadingResult.data?.let {
                        //_dashboardModel.value?.userAuthToken = it.userAuthToken
                        _uiState.value = FlatReadingUiState.Success(it)
                    } ?: run {
                        _uiState.value = FlatReadingUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    Log.e("DEBUG_LOG", "Error Data ${flatReadingResult.data}")
                    flatReadingResult.message?.let {
                        _uiState.value = FlatReadingUiState.Error(it)
                    } ?: run {
                        _uiState.value = FlatReadingUiState.Error("Unknown error")
                    }
                }
            }
        }
    }

    fun onUiEvent(flatReadingUiEvent: FlatReadingUiEvent) {
        when(flatReadingUiEvent) {
            is FlatReadingUiEvent.SubmitPrevious -> {}
            is FlatReadingUiEvent.SubmitNext -> {}
            is FlatReadingUiEvent.Submit -> {
                val flatReadingRequest: FlatReadingRequest = (flatReadingUiEvent as FlatReadingUiEvent.Submit).flatReadingRequest
                getFlatReadingNext(
                    userAuthToken = flatReadingRequest.userAuthToken,
                    categoryId = flatReadingRequest.categoryId,
                    itemId = flatReadingRequest.itemId,
                    questionId = flatReadingRequest.questionId,
                )
            }
        }
    }

    private fun getFlatReadingNext(userAuthToken: String? = "", categoryId: String, itemId: String, questionId: String) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = FlatReadingUiState.Loading
            val flatReadingResult = flatReadingUseCase.executeNext(userAuthToken, categoryId, itemId, questionId)
            when(flatReadingResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Loading Data ${flatReadingResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Success Data ${flatReadingResult.data}")
                    //_dashboardModel.value = dashboardResult.data
                    //_dashboardModel.postValue(flatReadingResult.data)
                    flatReadingResult.data?.let {
                        //_dashboardModel.value?.userAuthToken = it.userAuthToken
                        _uiState.value = FlatReadingUiState.Success(it)
                    } ?: run {
                        _uiState.value = FlatReadingUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    Log.e("DEBUG_LOG", "Error Data ${flatReadingResult.data}")
                    flatReadingResult.message?.let {
                        _uiState.value = FlatReadingUiState.Error(it)
                    } ?: run {
                        _uiState.value = FlatReadingUiState.Error("Unknown error")
                    }
                }
            }
        }
    }
}
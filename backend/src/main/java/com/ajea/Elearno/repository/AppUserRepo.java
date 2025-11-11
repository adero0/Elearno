package com.ajea.Elearno.repository;

import com.ajea.Elearno.models.AppUser;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;
import java.util.Optional;

public interface AppUserRepo extends JpaRepository<AppUser, Long> {
    @Override
    Optional<AppUser> findById(Long aLong);

    @Override
    List<AppUser> findAll();

    Optional<AppUser> findByUsername(String username);
}

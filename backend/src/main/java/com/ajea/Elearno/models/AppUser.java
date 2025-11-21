package com.ajea.Elearno.models;

import com.ajea.Elearno.models.enums.LearningStyle;
import com.ajea.Elearno.models.enums.UserRole;
import jakarta.persistence.*;
import lombok.Data;

import java.util.Set;


@Entity
@Data
public class AppUser {

    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE, generator = "app_user_seq")
    @SequenceGenerator(name = "app_user_seq", sequenceName = "app_user_id_seq", allocationSize = 1)
    private Long id;

    private String username;

    private String password;

    @Enumerated(EnumType.STRING)
    private UserRole role;

    private int visual;

    private int kinesthetic;

    private int auditory;

    private boolean hasTakenQuiz = false;

    @ManyToMany
    @JoinTable(
            name = "learning_material_completed",
            joinColumns = @JoinColumn(name = "user_id"),
            inverseJoinColumns = @JoinColumn(name = "material_id")
    )
    private Set<LearningMaterial> completedMaterials;
}
